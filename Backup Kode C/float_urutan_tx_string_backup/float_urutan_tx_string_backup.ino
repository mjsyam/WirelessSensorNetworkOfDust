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
uint8_t payload[4] = { 0, 0, 0, 0};
 
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

float h = 0.554;

void setup() {
  pinMode(statusrxLed, OUTPUT);
  pinMode(statustxLed, OUTPUT);
  pinMode(errorLed, OUTPUT);
  pinMode(yellowLed, OUTPUT);
  pinMode(redLed, OUTPUT);
  Serial.begin(9600);
  xbee.setSerial(Serial);
  srand (static_cast <unsigned> (0));
  bool_test = 1;
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

void hitung() {
   //h = 0.554; //h + 0.5;
   h = static_cast <float> (rand()) / static_cast <float> (RAND_MAX);
  //if ( h>10 )
  //     h = 0.554;
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

void readPacketRemote() {
   xbee.readPacket();
  Serial.println("Packet is read");

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

char * floatToString(char * outstr, double val, byte precision, byte widthp){
  char temp[16]; //increase this if you need more digits than 15
  byte i;

  temp[0]='\0';
  outstr[0]='\0';

  if(val < 0.0){
    strcpy(outstr,"-\0");  //print "-" sign
    val *= -1;
  }

  if( precision == 0) {
    strcat(outstr, ltoa(round(val),temp,10));  //prints the int part
  }
  else {
    unsigned long frac, mult = 1;
    byte padding = precision-1;
    
    while (precision--)
      mult *= 10;

    val += 0.5/(float)mult;      // compute rounding factor
    
    strcat(outstr, ltoa(floor(val),temp,10));  //prints the integer part without rounding
    strcat(outstr, ".\0"); // print the decimal point

    frac = (val - floor(val)) * mult;

    unsigned long frac1 = frac;

    while(frac1 /= 10) 
      padding--;

    while(padding--) 
      strcat(outstr,"0\0");    // print padding zeros

    strcat(outstr,ltoa(frac,temp,10));  // print fraction part
  }

  // generate width space padding 
  if ((widthp != 0)&&(widthp >= strlen(outstr))){
    byte J=0;
    J = widthp - strlen(outstr);

    for (i=0; i< J; i++) {
      temp[i] = ' ';
    }

    temp[i++] = '\0';
    strcat(temp,outstr);
    strcpy(outstr,temp);
  }

  return outstr;
}

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
