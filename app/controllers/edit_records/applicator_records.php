<?php

// applicator_records.php 
// Pseudocode 
// case a: same applicator, different output
    # update record
    # update applicator_records (palitan output) 
    # update cumulative (either dagdag or bawas) 
// case b: different applicator 
    # update record (mababago yung app1_id) 
    # delete old app1 record sa applicator_outputs 
    # decrement monitor_applicator ng previous output 
    # create new app1 record sa applicator_outputs 
    # increment monitor_applicator ng bagong output 

// Check for case for app2 
// case a: same applicator, different output 
    # update record (yung date mainly ang mababago since same applicator) 
    # update applicator_records (palitan output)
    # update cumulative (either dagdag or bawas)
// case b: different applicator
    # update record (mababago yung app1_id)
    # delete old app1 record sa applicator_outputs
    # decrement monitor_applicator ng previous output
    # create new app1 record sa applicator_outputs
    # increment monitor_applicator ng bagong output
// case c: none - new
    # update record para madagdag yung bagong app2_id
    # create_record para sa app2 
    # increment monitor_applicator para madagdag yung bagong output
// case d: old - none
    # update record_output para matanggal yung old app2_id
    # delete record
    # decrement monitor_applicator 