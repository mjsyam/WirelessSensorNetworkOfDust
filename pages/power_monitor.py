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
from datetime import datetime
import serial
import struct
from subprocess import call #import module for executing shell command
 
# PORT = 'COM5' #Select the correct COM
PORT = 'COM14' #'/dev/tty.usbserial-A703D14M'
BAUD_RATE = 9600
 
def hex(bindata):
    return ''.join('%02x' % ord(byte) for byte in bindata)
 
# Open serial port
ser = serial.Serial(PORT, BAUD_RATE)
 
# Create API object
xbee = ZigBee(ser,escaped=True)

print 'sensor ID', '\t', 'Year', '\t','Month','\t', 'Day','\t', 'Hour','\t', 'Minute','\t', 'Second','\t\t', 'Power', '\t\t', 'Voltage', '\t', 'Current', '\t', 'Energy', '\t\t', 'Temp', '\t', 'Humidity', ' \t', 'Intensity'
# Continuously read and print packets
while True:
    try:
        response = xbee.wait_read_frame()
        sa = hex(response['source_addr_long'][4:])
        rf = hex(response['rf_data'])
        datalength=len(rf)
        # if datalength is compatible with two floats
        # then unpack the 4 byte chunks into floats
        if datalength==104:
            #print 'test'
            y=struct.unpack('i',response['rf_data'][0:4])[0]
            m=struct.unpack('i',response['rf_data'][4:8])[0]
            d=struct.unpack('i',response['rf_data'][8:12])[0]
            ho=struct.unpack('i',response['rf_data'][12:16])[0]
            mi=struct.unpack('i',response['rf_data'][16:20])[0]
            se=struct.unpack('i',response['rf_data'][20:24])[0]
            p=struct.unpack('f',response['rf_data'][24:28])[0]
            v=struct.unpack('f',response['rf_data'][28:32])[0]
            c=struct.unpack('f',response['rf_data'][32:36])[0]
            e=struct.unpack('f',response['rf_data'][36:40])[0]
            t=struct.unpack('f',response['rf_data'][40:44])[0]
            hu=struct.unpack('f',response['rf_data'][44:48])[0]
            it=struct.unpack('f',response['rf_data'][48:])[0]
            #it= hex(response['rf_data'][48:52])
            #it=float.fromhex(it2)
            #it1=float.fromhex(it)        
            #print sa,' ',rf,' t=',t,'h=',h
            #ye=round(y,2)
           # print sa, '\t', str(datetime.now().date()), '\t', str(datetime.now().time()) ,w ,'\t', v, '\t' ,i, '\t', k #' pf=',pf
            print sa, '\t', y, '\t',m,'\t', d,'\t', ho,'\t', mi,'\t', se,'\t\t', p, '\t', v, '\t', c, '\t', e, '\t', t, '\t', hu, ' \t', it
            # execute php command for inserting data to MySQL DB

            call('php /xampp/htdocs/sresysv2/pages/db.php ' +
                 sa + ' ' +
                 str(y) + ' ' +
                 str(m) + ' ' +
                 str(d) + ' ' +
                 str(ho) + ' ' +
                 str(mi) + ' ' +
                 str(se) + ' ' +
                 str(p) + ' ' +
                 str(v) + ' ' +
                 str(c) + ' ' +
                 str(e) + ' ' +
                 str(t) + ' ' +
                 str(hu) + ' ' +
                 str(it)
                , shell=True)
            
        # if it is not two floats show me what I received
        else:
           print 'test2'
           print sa,' ',rf
           print len(rf)
    except KeyboardInterrupt:
        break
         
ser.close()
