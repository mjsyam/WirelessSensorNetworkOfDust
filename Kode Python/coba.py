    #! /usr/bin/python
 
"""
HumidityTempZigBee.py
 
Copyright 2012, Helmut Strey
 
This is a python script that receives and outputs the humidity and temperature
that was wirelessly transmitted from a DHT 22 / ZigBee RF module.
 
HumindityTempZigBee is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.
 
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with this program.  If not, see http://www.gnu.org/licenses/.
 
"""
 
from xbee import ZigBee
import serial
import struct
import MySQLdb
from datetime import datetime
 
PORT = 'COM20' #Select the correct COM
BAUD_RATE = 9600

# Koneksi Ke Database MySQL
conn = MySQLdb.connect(host= "localhost",
                  user="root",
                  passwd="",
                  db="sensor_debu")
x = conn.cursor()

 
def hex(bindata):
    return ''.join('%02x' % ord(byte) for byte in bindata)
 
# Open serial port
ser = serial.Serial(PORT, BAUD_RATE)
 
# Create API object
xbee = ZigBee(ser,escaped=True)
 
# Continuously read and print packets
while True:
    try:
        response = xbee.wait_read_frame()
        sa = hex(response['source_addr_long'][4:])
        rf = hex(response['rf_data'])
        datalength=len(rf)
        # if datalength is compatible with two floats
        # then unpack the 4 byte chunks into floats
        if datalength==16:

            l=struct.unpack('f',response['rf_data'][0:4])[0]
            c=struct.unpack('f',response['rf_data'][4:8])[0]

            # Print data lowpulseoccupancy dan concentration
            print datetime.now(),' ',sa,' ',rf,' c=',c,'l=',l #' c=', c

            # Insert data lowpulseoccupancy dan concentration ke database
            try:
               x.execute("""INSERT INTO data_sensor(waktu, l, c) VALUES (%s,%s,%s)""",(datetime.now(),c,l))
               conn.commit()
            except:
               conn.rollback()
            #conn.close()
        # if it is not two floats show me what I received
        else:
            print datetime.now(),' ',sa,' ',rf
    except KeyboardInterrupt:
        break
         
ser.close()
