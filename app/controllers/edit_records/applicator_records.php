<?php

// Pseudocode 
// case a: same applicator, different output
    # update applicator_outputs (palitan output) === DONE 
    # update cumulative (either dagdag or bawas) === NOT YET
// case b: different applicator 
    # update old app1 output to new app1 output in applicator_outputs === DONE
    # decrement monitor_applicator ng previous output === NOT YET
    # increment monitor_applicator ng bagong output === NOT YET

// Check for case for app2 
// case a: same applicator, different output 
    # update applicator_outputs (palitan output) === DONE
    # update cumulative (either dagdag or bawas) === NOT YET
// case b: different applicator
    # update old app2 output to new app2 output in applicator_outputs === DONE
    # decrement monitor_applicator ng previous output === NOT YET
    # increment monitor_applicator ng bagong output === NOT YET
// case c: none - new
    # create new applicator_output
    # increment monitor_applicator for new app2 === NOT YET
// case d: old - none
    # remove previous app2 output in applicator_outputs === NOT YET
    # decrement monitor_applicator === NOT YET

// ===== APPLICATOR 1 =====
$app1_case = null;
if ($app1 === $prev_app1 && $app1_output !== $prev_app1_output) {
    $app1_case = "A"; // same applicator, different output
} elseif ($app1 !== $prev_app1) {
    $app1_case = "B"; // different applicator
}

// ===== APPLICATOR 2 =====
$app2_case = null;
if ($app2 === $prev_app2 && $app2_output !== $prev_app2_output) {
    $app2_case = "A"; // same applicator, different output
} elseif ($app2 !== $prev_app2 && !empty($prev_app2)) {
    $app2_case = "B"; // different applicator
} elseif (!empty($app2) && empty($prev_app2)) {
    $app2_case = "C"; // none → new
} elseif (empty($app2) && !empty($prev_app2)) {
    $app2_case = "D"; // old → none
}

// ===== APPLICATOR 1 =====
switch ($app1_case) {
    case "A": // same applicator, different output
        # update applicator_outputs (palitan output) === DONE 
        # update cumulative (either dagdag or bawas) === NOT YET
        break;

    case "B":
        # update old app1 output to new app1 output in applicator_outputs === DONE
        # decrement monitor_applicator ng previous output === NOT YET
        # increment monitor_applicator ng bagong output === NOT YET
        break;
} 

// ===== APPLICATOR 2 =====
switch ($app2_case) {
    case "A": // same applicator, different output
        # update applicator_outputs (palitan output) === DONE 
        # update cumulative (either dagdag or bawas) === NOT YET
        break;

    case "B":
        # update old app1 output to new app1 output in applicator_outputs === DONE
        # decrement monitor_applicator ng previous output === NOT YET
        # increment monitor_applicator ng bagong output === NOT YET
        break;

    case "C": // same applicator, different output
        # create new applicator_output
        # increment monitor_applicator for new app2 === NOT YET
        break;

    case "D":
        # remove previous app2 output in applicator_outputs === NOT YET
        # decrement monitor_applicator === NOT YET
        break;
} 