<?php

// machine_records.php 
// case a: same machine, different output
    # update record (yung time lang ayusin)
    # update machine_records (palitan output) 
    # update cumulative (either dagdag or bawas) 
// case b: different machine 
    # update record (mababago yung machine_id) 
    # delete old machine_id record sa machine_outputs 
    # decrement monitor_machines ng previous output 
    # create new app1 record sa applicator_outputs 
    # increment monitor_machines ng bagong output 