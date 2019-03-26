##Author:   Drew D. Lenhart
##Desc:     Performs simple light test of temperature unit
## e.g.

import time

blinks = 1
limit = 11

while True:
    ## do some real light blinking here###
    print blinks,
    blinks = blinks + 1
    if blinks >= limit:
        break    
    time.sleep(1)

#check the count

if blinks == limit:
    print 'OK'
else:
    print 'ERR'



