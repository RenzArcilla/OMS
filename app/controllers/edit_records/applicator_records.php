<?php

// For app1
// case a: same applicator, different output
    # update applicator_outputs (palitan output)
    # update cumulative (either dagdag or bawas)
// case b: different applicator 
    # update old app1 output to new app1 output in applicator_outputs
    # decrement monitor_applicator ng previous output
    # increment monitor_applicator ng bagong output

// For app2 
// case a: same applicator, different output 
    # update applicator_outputs (palitan output)
    # update cumulative (either dagdag or bawas)
// case b: different applicator
    # update old app2 output to new app2 output in applicator_outputs
    # decrement monitor_applicator ng previous output
    # increment monitor_applicator ng bagong output
// case c: none - new
    # create new applicator_output
    # increment monitor_applicator for new app2
// case d: old - none
    # remove previous app2 output in applicator_outputs
    # decrement monitor_applicator


// APPLICATOR 1: Check for edit case
$app1_case = null;
if ($app1 === $prev_app1 && $app1_output !== $prev_app1_output) {
    $app1_case = "A"; // same applicator, different output
} elseif ($app1 !== $prev_app1) {
    $app1_case = "B"; // different applicator
}

// APPLICATOR 2: Check for edit case
$app2_case = null;
if ($app2 === $prev_app2 && $app2_output !== $prev_app2_output) {
    $app2_case = "A"; // same applicator, different output
} elseif (empty($app2) && !empty($prev_app2)) {
    $app2_case = "D"; // old → none
} elseif ($app2 !== $prev_app2 && !empty($prev_app2)) {
    $app2_case = "B"; // different applicator
} elseif (!empty($app2) && empty($prev_app2)) {
    $app2_case = "C"; // none → new
}



// APPLICATOR 1 
switch ($app1_case) {
    case "A": // same applicator, different output
        # update applicator_outputs (palitan output)
            $update_app1_output_result = updateApplicatorOutput($app1_data,
                                    $app1_output, $record_id, $prev_app1_data);
            if (is_string($update_app1_output_result)) {
                throw new Exception($update_app1_output_result);
            }
        # update cumulative (either dagdag or bawas)
            $direction = "increment";
            $applicator_output = $app1_output - $prev_app1_output;
            if ($applicator_output < 0 ) {
                $direction = "decrement";
            }
            $result = monitorApplicatorOutput($app1_data, $applicator_output, $direction);
            if (is_string($result)) {
                throw new Exception($result);
            }
        break;

    case "B": // different applicator
        # update old app1 output to new app1 output in applicator_outputs
            $update_app1_output_result = updateApplicatorOutput($app1_data,
                                    $app1_output, $record_id, $prev_app1_data);
            if (is_string($update_app1_output_result)) {
                throw new Exception($update_app1_output_result);
            }
        # decrement monitor_applicator ng previous output === NOT YET
            $result = monitorApplicatorOutput($prev_app1_data, $prev_app1_output, "decrement");
            if (is_string($result)) {
                throw new Exception($result);
            }
        # increment monitor_applicator ng bagong output === NOT YET
            $result = monitorApplicatorOutput($app1_data, $app1_output, "increment");
            if (is_string($result)) {
                throw new Exception($result);
            }
        break;
} 

// ===== APPLICATOR 2 =====
switch ($app2_case) {
    case "A": // same applicator, different output
        # update applicator_outputs (palitan output)
            $update_app2_output_result = updateApplicatorOutput($app2_data,
                                    $app2_output, $record_id, $prev_app2_data);
            if (is_string($update_app2_output_result)) {
                throw new Exception($update_app2_output_result);
            }
        # update cumulative (either dagdag or bawas)
            $direction = "increment";
            $applicator_output = $app2_output - $prev_app2_output;
            if ($applicator_output < 0 ) {
                $direction = "decrement";
            }
            $result = monitorApplicatorOutput($app2_data, $applicator_output, $direction);
            if (is_string($result)) {
                throw new Exception($result);
            }
        break;

    case "B":
        # update old app1 output to new app1 output in applicator_outputs
            $update_app2_output_result = updateApplicatorOutput($app2_data,
                                    $app2_output, $record_id, $prev_app2_data);
            if (is_string($update_app2_output_result)) {
                throw new Exception($update_app2_output_result);
            }
        # decrement monitor_applicator ng previous output
            $result = monitorApplicatorOutput($prev_app2_data, $prev_app2_output, "decrement");
            if (is_string($result)) {
                throw new Exception($result);
            }
        # increment monitor_applicator ng bagong output
            $result = monitorApplicatorOutput($app2_data, $app2_output, "increment");
            if (is_string($result)) {
                throw new Exception($result);
            }
        break;

    case "C": // none → new
        # create new applicator_output
            $result = submitApplicatorOutput($app2_data, $app2_output, $record_id);
            if (is_string($result)) {
                throw new Exception($result);
            }
        # increment monitor_applicator for new app2
            $result = monitorApplicatorOutput($app2_data, $app2_output, "increment");
            if (is_string($result)) {
                throw new Exception($result);
            }
        break;

    case "D": // old → none
        # remove previous app2 output in applicator_outputs
            $result = deleteApplicatorOutputs($prev_app2_data["applicator_id"], $record_id);
            if (is_string($result)) {
                throw new Exception($result);
            }
        # decrement monitor_applicator
            $result = monitorApplicatorOutput($prev_app2_data, $prev_app2_output, "decrement");
            if (is_string($result)) {
                throw new Exception($result);
            }
        break;
} 