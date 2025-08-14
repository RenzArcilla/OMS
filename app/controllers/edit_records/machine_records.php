<?php

// Pseudocode 
// case a: same machine, different output
    # update machine_records (palitan output)
    # update cumulative (either dagdag or bawas)
// case b: different machine 
    # update old machine output to new machine output in machine_outputs
    # decrement monitor_machines ng previous output
    # increment monitor_machines ng bagong output

$machine_case = null;
if ($machine === $prev_machine && $machine_output !== $prev_machine_output) {
    $machine_case = "A"; // same machine, different output
} elseif ($machine !== $prev_machine) {
    $machine_case = "B"; // different machine 
}

// ===== MACHINE =====
switch ($machine_case) {
    case "A": // same machine, different output
        # update machine_outputs (palitan output)
            $update_machine_output_result = updateMachineOutput(
                        $machine_data, $machine_output, $record_id);
            if (is_string($update_machine_output_result)) {
                throw new Exception($update_machine_output_result);
            } 
        # update cumulative (either dagdag or bawas) 
            $direction = "increment";
            $difference = $machine_output - $prev_machine_output;
            if ($difference < 0 ) {
                $direction = "decrement";
            }
            $result = monitorMachineOutput($machine_data, $difference, $direction);
            if (is_string($result)) {
                throw new Exception($result);
            }
        break;

    case "B":
        # update old machine output to new machine output in machine_outputs
        # decrement monitor_machines ng previous output
        # increment monitor_machines ng bagong output
        break;
}