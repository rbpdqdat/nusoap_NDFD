#!/usr/bin/python3
from xml.dom import minidom
import csv
import sys
import os.path
import time
import dateutil.parser
import datetime
from datetime import timedelta
from collections import OrderedDict
from time import strftime

filelist=['SPS','PSCO']
#test5=['SPS']
#for arg in sys.argv:
for arg in filelist:
    fname=arg
#    xmlfile='/var/www/nusoap/'+fname+'_ndfd.xml'
    xmlfile='/metero/software/tomcat/apache-tomcat-6.0.36/webapps/ROOT/nusoap/'+fname+'_ndfd.xml'

    if (os.path.isfile(xmlfile)):
        print (xmlfile+" File Exists.  Proceed!\n")
        xmldoc = minidom.parse(xmlfile)
        itemlist = xmldoc.getElementsByTagName('data')
        filelocation='/metero/software/tomcat/apache-tomcat-6.0.36/webapps/ROOT/nusoap/'+fname+'_ndfd.csv'
        print ("Create New File: "+filelocation+"\n")
        cloud=open(filelocation,"w")
        d=OrderedDict()
        newd=OrderedDict()
        for s in itemlist:
            for t in range(len(s.getElementsByTagName('start-valid-time'))):
        #cloud.write(s.attributes['start-valid-time'].value +",")
        #cloud.write(s.getElementsByTagName('ForecastTime')[0].childNodes[0].data+",")
        #cloud.write(s.getElementsByTagName('start-valid-time')[0].childNodes[0].data+",")
                 #cloud.write(s.getElementsByTagName('start-valid-time')[t].childNodes[0].data+",")
        #cloud.write(s.getElementsByTagName('value')[0].childNodes[0].data+"\n")
                 #cloud.write(s.getElementsByTagName('value')[t].childNodes[0].data+"\n")
                fcs=(s.getElementsByTagName('start-valid-time')[t].childNodes[0].data+",")
                k=dateutil.parser.parse(fcs)
                skyp=(int(s.getElementsByTagName('value')[t].childNodes[0].data))/100.0
                d.update({k:skyp})
        keylist=list(d.keys())
        lastdate=keylist[-1]
        firstdate=keylist[0]
        print(lastdate)
        print(firstdate)
        diff=lastdate-firstdate
        diffhours=diff.total_seconds()/60/60
        print(diffhours)
        t=0
        while t <=diffhours:
            newtime = firstdate + timedelta(hours=t)
            if newtime in d.keys():
                newd.update({newtime:d[newtime]})
            else:
                newd.update({newtime:None})
            t+=1    
        for newkey in newd:
            timestring=newkey.strftime('%Y/%m/%d,%H')
            if (newd[newkey]==None):
                newd.update({newkey:newd[newkey - timedelta(hours=1)]})
            cloud.write(timestring)
            cloud.write(","+str(newd[newkey])+"\n")
        cloud.close()
        print("Closing File: fileloction.  Proceed to next file\n")
    else:
        print("There was no file named: "+xmlfile+"\n")

