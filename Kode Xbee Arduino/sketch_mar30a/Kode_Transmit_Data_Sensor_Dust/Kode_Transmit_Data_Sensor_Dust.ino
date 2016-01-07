#include <XBee.h>

XBee xbee = XBee();

uint8_t payload[8] = { 0, 0, 0, 0, 0, 0, 0, 0};


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

int pin = 8;
unsigned long duration;
unsigned long starttime;
unsigned long sampletime_ms = 30000;
unsigned long lowpulseoccupancy = 0;
float ratio = 0;
float concentration = 0;

void setup() {
  //Serial.begin(9600);
  pinMode(8,INPUT);
  //starttime = millis();
  
  Serial.begin(9600);
  xbee.setSerial(Serial);
  srand (static_cast <unsigned> (0));
  //xbee.begin(9600);
}

void loop() {
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
    
    //masukan data untuk dikirim
    payload[0] = lowpulseoccupancy >> 8 & 0xff;
    payload[1] = lowpulseoccupancy & 0xff;
    
    //payload[2] = ratio >> 8 & 0xff;
    //payload[3] = ratio & 0xff;
    
    //payload[4] = concentration >> 8 & 0xff;
    //payload[5] = concentration & 0xff;
    
    //kirim data
    xbee.send(zbTx);
    
    //cek status pengiriman lewat lampu
    flashLed(statustxLed, 1, 100);
    
    //cek status respon selama 5 detik
    if(xbee.readPacket(5000)){
      if(xbee.getResponse().getApiId()==ZB_TX_STATUS_RESPONSE){
          xbee.getResponse().getZBTxStatusResponse(txStatus);
      }
      
      if (txStatus.getDeliveryStatus() == SUCCESS) {
        flashLed(statustxLed, 5, 50);
      }else{
        flashLed(errorLed, 3, 500);
      }
    }else{
         flashLed(errorLed, 2, 50);
    }
  }
  delay(1000);
}


void readSensor(){

}
