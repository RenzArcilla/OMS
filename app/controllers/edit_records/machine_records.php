<?php

// Pseudocode 
// case a: same machine, different output
    # update machine_records (palitan output) === DONE
    # update cumulative (either dagdag or bawas) === NOT YET
// case b: different machine 
    # update old machine output to new machine output in machine_outputs === DONE
    # decrement monitor_machines ng previous output === NOT YET
    # increment monitor_machines ng bagong output === NOT YEt

$machine_case = null;
if ($machine === $prev_machine && $machine_output !== $prev_machine_output) {
    $machine_case = "A"; // same machine, different output
} elseif ($machine !== $prev_machine) {
    $machine_case = "B"; // different machine 
}

// ===== MACHINE =====
switch ($machine_case) {
    case "A": // same machine, different output
        # update machine_outputs (palitan output) === DONE 
        # update cumulative (either dagdag or bawas) === NOT YET
        break;

    case "B":
        # update old app1 output to new app1 output in machine_outputs === DONE
        # decrement monitor_machine ng previous output === NOT YET
        # increment monitor_machine ng bagong output === NOT YET
        break;
} 