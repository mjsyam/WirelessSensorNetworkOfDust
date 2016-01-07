#include "XBee.h"
//#include <cstdlib>
#include <stdio.h>

/*
This example is for Series 2 XBee
 Sends a ZB TX request with the value of analogRead(pin5) and checks the status response for success
*/
 
// create the XBee object
XBee xbee = XBee();

uint32_t ID;
 
// we are going to send two floats of 4 bytes each
uint8_t payload[4] = {0, 0, 0};
 
// union to convery float to byte string
union u_tag {
    uint8_t b[4];
    float fval;
} u;

float floatVariable;
char test[50]; // we're going to use this to hold our string
char buff[50];
boolean bool_test;

// SH + SL Address of receiving XBee
XBeeAddress64 addr64 = XBeeAddress64(0x00000000, 0x00000000); //masukkan alamat xbee coordinator
//XBeeAddress64 addr64 = XBeeAddress64(0x0013A200, 0x40A7DB96); 
//create the packet
//Serial.println(addr64, HEX);
ZBTxRequest zbTx = ZBTxRequest(addr64, payload, sizeof(payload));
//ZBTxRequest zbTx = ZBTxRequest(addr64, (uint8_t *)buff, strlen(buff));
//create the Tx request
ZBTxStatusResponse txStatus = ZBTxStatusResponse();


//receive packet
// create reusable response objects for responses we expect to handle
XBeeResponse response = XBeeResponse();
ZBRxResponse rx = ZBRxResponse();


int pin = 8;
unsigned long duration;
unsigned long starttime;
unsigned long sampletime_ms = 30000;
unsigned long lowpulseoccupancy = 0;
float ratio = 0;
float concentration = 0;


void setup() {
  Serial.begin(9600);
  pinMode(8,INPUT);
  
  xbee.setSerial(Serial);
  starttime = millis();
}

void loop() {
 // Reading temperature or humidity takes about 250 milliseconds!
  // Sensor readings may also be up to 2 seconds 'old' (its a very slow sensor)
  sendPacket();
  delay (500);
  //continuously reads packets, looking for ZB Receive
  //readPacketRemote();
  
  delay(1000);
  
  
  duration = pulseIn(pin, LOW);
  lowpulseoccupancy = lowpulseoccupancy+duration;

  if ((millis()-starttime) > sampletime_ms)
  {
    ratio = lowpulseoccupancy/(sampletime_ms*10.0);  // Integer percentage 0=>100
    concentration = 1.1*pow(ratio,3)-3.8*pow(ratio,2)+520*ratio+0.62; // using spec sheet curve
    Serial.print(lowpulseoccupancy);
    Serial.print(",");
    Serial.print(ratio);
    Serial.print(",");
    Serial.println(concentration);
    lowpulseoccupancy = 0;
    starttime = millis();
  }
}

void sendPacket() {
   hitung();
   // convert humidity into a byte array and copy it into the payload array
  
   sprintf(buff, "Sent: ");
   
   strcat(buff, floatToString(test, h, 3, true));
   Serial.println("Print buffer1");
   Serial.println(buff);
   strcat(buff, "; ");
   strcat(buff, floatToString(test, bool_test, 0, true));
   Serial.println("Print buffer2");
   Serial.println(buff);
  
   // Serial.print("floatToString: " );
   //Serial.println(floatToString(buff, h, 3, true));
    u.fval = h;
    Serial.print("h: " );
    Serial.println(h,3);
    //sprintf(buff, "String sent: %d.%3d\n", 
    //      int(h), frac(h));  // and finally the integer
    //Serial.println("Print buffer");
    //printbuffer(buff);
    
    Serial.print("Random h: ");
    Serial.println(h,3);
    for (int i=0;i<4;i++){
      payload[i]=u.b[i];
    }
    ZBTxRequest zbTx = ZBTxRequest(addr64, (uint8_t *)buff, strlen(buff)); 
  
    xbee.send(zbTx);
    
    // flash TX indicator
    flashLed(statustxLed, 1, 1000);
    Serial.println();
    Serial.println("flashLed turn ON");
    
    // after sending a tx request, we expect a status response
    // wait up to half second for the status response
    if (xbee.readPacket(500)) {
      // got a response!
       Serial.println("Packet received");
      if (xbee.getResponse().isAvailable()) {
        Serial.print("xbee.getResponse().isAvailable(): ");
        Serial.println(xbee.getResponse().isAvailable());
      }
       // got something
      
      // should be a znet tx status             
      if (xbee.getResponse().getApiId() == ZB_TX_STATUS_RESPONSE) {
        xbee.getResponse().getZBTxStatusResponse(txStatus);
        Serial.print("xbee.getResponse().getApiId(): ");
        Serial.println(xbee.getResponse().getApiId());
        Serial.print("ZB_TX_STATUS_RESPONSE: ");
        Serial.println(ZB_TX_STATUS_RESPONSE);
        
        //ZNetRxResponse rx = 
        //(ZNetRxResponse)(XBeeResponse) response;
        // get the delivery status, the fifth byte
        if (txStatus.getDeliveryStatus() == SUCCESS) {
          //Serial.println(txStatus.getDeliveryStatus());
          // success.  time to celebrate
          flashLed(statustxLed, 5, 50);
          Serial.println("Success.  time to celebrate"); 
          
        } 
        else {
          // the remote XBee did not receive our packet. is it powered on?
          flashLed(errorLed, 3, 500);
          Serial.println("The remote XBee did not receive our packet. Maybe it powered off"); 
        }
      }
    } 
    else if (xbee.getResponse().isError()) {
      Serial.print("Error reading packet.  Error code: ");  
      Serial.println(xbee.getResponse().getErrorCode());
    } 
    else {
      // local XBee did not provide a timely TX Status Response -- should not happen
      flashLed(errorLed, 2, 50);
      Serial.println("FlashLed error"); 
      Serial.println("local XBee did not provide a timely TX Status Response"); 
    }
}

