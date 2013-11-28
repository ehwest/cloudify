#BASH script for running on beaglebone

export PATH=/usr/local/bin:/usr/bin:/bin:/usr/local/sbin:/usr/sbin:/sbin
SMS=`python /usr/bin/parseSMS.py`
ADDR=`echo $SMS | sed -e 's@.*MSG:@\n@' |grep http |tail -1 | tr -d ' '`
echo $ADDR
if [ -z ${ADDR} ] 
  then
         D=`date`
         echo no poll ${D}
         echo no poll ${D} >> /home/root/logfile.txt
  else
         D=`date`
         echo successful poll ${D} 
         echo successful poll ${D} >> /home/root/logfile.txt
         curl -k ${ADDR} > /home/root/smsconfigfile.txt
         cat /home/root/smsconfigfile.txt >> logfile.txt
         cat /home/root/smsconfigfile.txt
fi

