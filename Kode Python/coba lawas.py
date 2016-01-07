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
from datetime import datetime
 
PORT = 'COM21' #Select the correct COM
BAUD_RATE = 9600
 
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
            h=struct.unpack('f',response['rf_data'][0:4])[0]
            t=struct.unpack('f',response['rf_data'][4:])[0]
            print datetime.now(),' ',sa,' ',rf,' t=',t,'h=',h
        # if it is not two floats show me what I received
        else:
            print datetime.now(),' ',sa,' ',rf
    except KeyboardInterrupt:
        break
         
ser.close()
