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
 
// Set floats untuk pengiriman 2 data
uint8_t payload[8] = { 0, 0, 0, 0, 0, 0, 0, 0};
 
// union to convery float to byte string
union u_tag {
    uint8_t b[4];
    float fval;
} u;

float floatVariable;
char buff[50]; // we're going to use this to hold our string

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
//Rx64Response rx64 = Rx64Response(); 
//XBeeAddress64 adr64 = XBeeAddress64(); 
int yellowLed = 9;
int redLed = 10;
int statusrxLed = 11;
int errorLed = 12;
int statustxLed = 13;


void flashLed(int pin, int times, int wait) {
 
  for (int i = 0; i < times; i++) {
    digitalWrite(pin, HIGH);
    delay(wait);
    digitalWrite(pin, LOW);
 
    if (i + 1 < times) {
      delay(wait);
    }
  }
}

//float h = 0.554;

//Variabel Data Sensor
int pin = 8; //Pin Data yang akan dikirim
unsigned long durn;
unsigned long starttime;
unsigned long sampletime_ms = 30000;
unsigned long l = 0; //lowpulseoccupancy 
float c = 0; //concentration

void setup() {
  pinMode(statusrxLed, OUTPUT);
  pinMode(statustxLed, OUTPUT);
  pinMode(errorLed, OUTPUT);
  Serial.begin(9600);
  xbee.setSerial(Serial);
  srand (static_cast <unsigned> (0));
  starttime = millis();
  //xbee.begin(9600);
}
 
void loop() {
  
  // Reading temperature or humidity takes about 250 milliseconds!
  // Sensor readings may also be up to 2 seconds 'old' (its a very slow sensor)
  sendPacket();
  delay (500);
  // continuously reads packets, looking for ZB Receive
  readPacketRemote();
  
  delay(1000);
}

void sendPacket() {
  //Perhitungan data-data sensor yang akan ditransmit
  durn = pulseIn(pin, LOW);
  l = l+durn;
	
  if ((millis()-starttime) > sampletime_ms){
	r = l/(sampletime_ms*10.0);  // Integer percentage 0=>100
    c = 1.1*pow(r,3)-3.8*pow(r,2)+520*r+0.62; // using spec sheet curve
    
    //Proses insert data-data sensor ke payload untuk dikirim
    u.fval = l;
    for (int i=0;i<4;i++){
      payload[i]=u.b[i];
    }
    
    u.fval = c;
    for (int i=0;i<4;i++){
      payload[i+4]=u.b[i];
    }
    
    ZBTxRequest zbTx = ZBTxRequest(addr64, (uint8_t *)buff, strlen(buff)); 
    xbee.send(zbTx);    


    Serial.print(l);
    Serial.print(",");
    Serial.print(r);
    Serial.print(",");
    Serial.println(c);	
    
    //ZBTxRequest zbTx = ZBTxRequest(addr64, (uint8_t *)buff, strlen(buff)); 
    xbee.send(zbTx);
    
    // flash TX indicator
    flashLed(statustxLed, 1, 1000);
    //Serial.println();
    //Serial.println("flashLed turn ON");
    
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
	l = 0;
    starttime = millis();
	}
}

void readPacketRemote() {
   xbee.readPacket();
  //Serial.println("Packet is read");

  if (xbee.getResponse().isAvailable()) {
  // got something
    Serial.print("xbee.getResponse().isAvailable(): ");
    Serial.println(xbee.getResponse().isAvailable());  
    // Andrew call the frame type ApiId, it's the first byte
    // of the frame specific data in the packet.
    Serial.print("Frame Type is: ");
    Serial.println(xbee.getResponse().getApiId(),HEX);
    if (xbee.getResponse().getApiId() == ZB_RX_RESPONSE) {
    // got a zb rx packet
      flashLed(statusrxLed, 3, 50);
      //Serial.print("xbee.getResponse().getApiId(): ");
      //Serial.println(xbee.getResponse().getApiId(),HEX);
      //Serial.print("ZB_RX_RESPONSE: ");
      //Serial.println(ZB_RX_RESPONSE, HEX);

      // now fill our zb rx class
      // now that you know it's a receive packet
      // fill in the values
      xbee.getResponse().getZBRxResponse(rx);
      // this is how you get the 64 bit address out of
      // the incoming packet so you know which device
      // it came from
      Serial.print("Got an rx packet from: ");
      XBeeAddress64 senderLongAddress = rx.getRemoteAddress64();
      print32Bits(senderLongAddress.getMsb());
      Serial.print(" ");
      print32Bits(senderLongAddress.getLsb());
      // this is how to get the sender's
      // 16 bit address and show it
      uint16_t senderShortAddress = rx.getRemoteAddress16();
      Serial.print(" (");
      print16Bits(senderShortAddress);
      Serial.println(")");
      //Serial.print("Remote address LSB: ");
      //Serial.println(rx.getRemoteAddress64().getLsb(), HEX);
      //Serial.print("Remote address MSB: ");
      //Serial.println(rx.getRemoteAddress64().getMsb(), HEX);
      //Serial.print("getDataLength: ");
      //Serial.println(rx.getDataLength());
      // The option byte is a bit field
      if (rx.getOption() & ZB_PACKET_ACKNOWLEDGED) {
      // the sender got an ACK
        Serial.println("Acknowledged packet");
        Serial.println(rx.getOption());
      }
      if (rx.getOption() & ZB_BROADCAST_PACKET){
        // This was a broadcast packet
        //Serial.println(rx.getOption(),HEX);
        Serial.println("Broadcast packet");
      }
        Serial.print("checksum is ");
        Serial.println(rx.getChecksum(), HEX);
       //Serial.println(); 
       // So, for example, you could do something like this:
       //handleXbeeRxMessage(rx.getData(), rx.getDataLength());
      Serial.print("getFrameDataLength: ");
      Serial.println(xbee.getResponse().getFrameDataLength());
      showFrameData();
      //Serial.print("frame: ");
      // print frame data
      //for (int i = 0; i < xbee.getResponse().getFrameDataLength(); i++) {
      //  Serial.print(xbee.getResponse().getFrameData()[i], HEX);
      //}
      //Serial.print("\n");
     
        // this is the packet length
        Serial.print("packet length is ");
        Serial.print(rx.getPacketLength(), DEC);
         // this is the payload length, probably
        // what you actually want to use
        Serial.print(", data payload length is ");
        Serial.println(rx.getDataLength(),DEC);
        
        
        // this is the actual data you sent
        
        Serial.println("Received Data: ");
        for (int i = 0; i < rx.getDataLength(); i++) {
          print8Bits(rx.getData()[i]);
          Serial.print(' ');
        }
        // and an ascii representation for those of us
        // that send text through the XBee
        Serial.println();
        for (int i= 0; i < rx.getDataLength(); i++){
          Serial.write(' ');
          if (iscntrl(rx.getData()[i]))
            Serial.write(' ');
          else
            Serial.write(rx.getData()[i]);
          Serial.write(' ');
        }
        Serial.println();
      Serial.print("data: ");
      //for (int i = 0; i < rx.getDataLength(); i++) {
        // Serial.print(rx.getData(i),HEX);
        // v.base[i] = rx.getData(i);
         //Serial.write(f_p[i]);
         //f_p[i] = Serial.read();
          //Serial.write(f_p[1]);
          //Serial.write(f_p[2]);
          //Serial.write(f_p[3]);
       //}
    }
  }
}
void print32Bits(uint32_t dw){
  print16Bits(dw >> 16);
  print16Bits(dw & 0xFFFF);
}

void print16Bits(uint16_t w){
  print8Bits(w >> 8);
  print8Bits(w & 0x00FF);
}

void print8Bits(byte c){
  uint8_t nibble = (c >> 4);
  if (nibble <= 9)
    Serial.write(nibble + 0x30);
  else
    Serial.write(nibble + 0x37);
     
  nibble = (uint8_t) (c & 0x0F);
  if (nibble <= 9)
    Serial.write(nibble + 0x30);
  else
    Serial.write(nibble + 0x37);
}

void handleXbeeRxMessage(uint8_t *data, uint8_t length){
  // this is just a stub to show how to get the data,
  // and is where you put your code to do something with
  // it.
  for (int i = 0; i < length; i++){
    Serial.print(data[i]);
  }
  Serial.println();
}

void showFrameData(){
  Serial.println("Incoming frame data:");
  for (int i = 0; i < xbee.getResponse().getFrameDataLength(); i++) {
    print8Bits(xbee.getResponse().getFrameData()[i]);
    Serial.print(' ');
  }
  Serial.println();
  for (int i= 0; i < xbee.getResponse().getFrameDataLength(); i++){
    Serial.write(' ');
    if (iscntrl(xbee.getResponse().getFrameData()[i]))
      Serial.write(' ');
    else
      Serial.write(xbee.getResponse().getFrameData()[i]);
    Serial.write(' ');
  }
  Serial.println();
}

// this little function will return the first two digits after the decimal
// point of a float as an int to help with sprintf() (won't work for negative values)
// the .005 is there for rounding.
int frac(float num){
  return( ((num + .0005) - (int)num) * 1000);
}
// this function prints the characters of a c string one at a time
// without any formatting to confuse or hide things
void printbuffer(char *buffer){
  while(*buffer){
    Serial.write(*buffer++);
  }
}
     //Serial.println(rx.getOption()); // + ", sender 64 address is " + ByteUtils.toBase16(rx.getRemoteAddress64().getAddress()) + ", remote 16-bit address is " + ByteUtils.toBase16(rx.getRemoteAddress16().getAddress()) + ", data is " + ByteUtils.toBase16(rx.getData()));
     //Serial.println(rx.getRemoteAddress64(), HEX);
     //ID = rx.getRemoteAddress64().getLsb(); 
     //Serial.println(rx.getRemoteAddress64().getLsb(), HEX);
     //Serial.println(rx.getRemoteAddress64().getMsb(), HEX);
     //Serial.println(rx.getRemoteAddress16().getLsb(), HEX);
     //Serial.println(rx.getDataLength());
 // }
//}
