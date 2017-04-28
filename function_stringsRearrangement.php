function exist_elem_in_array($elem_to_check, $array)
{
    foreach($array as $member)
        if($member == $elem_to_check)
            return true;

    return false;
}

function stringsRearrangement($inputArray)
{
    //
    // Complexity: O(NP)
    // best: O(n^2) [fails/false if nodes are disconnected]
    // worst: O(n!) [complete graph case]
    // Hamiltonian path problem
    //
    $index_map = array();
    $end_array = count($inputArray);
    $end = $end_array - 1;
    $end_string = strlen($inputArray[0]);
    $total_matches = 0;
    
    // populating map based on which indices match one another
    // indexes are added to corresponding arrays
    // **************************************************************
    for($i = 0; $i < $end_array; $i++)
        $index_map[$i] = array();

    for($i = 0; $i < $end; $i++)
    {
        for($j = $i+1; $j < $end_array; $j++)
        {
            $char_mismatches = 0;

            for($col = 0; $col < $end_string; $col++)
                if($inputArray[$i][$col] != $inputArray[$j][$col])
                    $char_mismatches += 1;
            
            if($char_mismatches == 1)
            {
                $index_map[$i][] = $j;
                $index_map[$j][] = $i;
            }
        }
    }
    // **************************************************************

    
    // printing list of matches of each index
    // in $index_map to console
    // **************************************************************
    // example:
    // ["ab", "bb", "aa"] =>
    // 0("ab"): 1,2
    // 1("bb"): 0
    // 2("aa"): 0
    for($row = 0; $row < $end_array; $row++)
    {
        echo $row;
        echo "(\"";
        echo $inputArray[$row];
        echo "\"): ";

        $last_element_in_row = end($index_map[$row]);
        foreach($index_map[$row] as $column)
        {
            echo $column;
            if($column != $last_element_in_row)
                echo ",";
        }
        echo "\n";
    }
    echo "\n";
    // **************************************************************
    

    // checking number of matches between indices and returning false
    // if there aren't enough
    // **************************************************************
    $num_indices_with_single_match = 0;
    for($i = 0; $i < $end_array; $i++)
    {
        $num_matches = count($index_map[$i]);
        if($num_matches == 1)
            $num_indices_with_single_match += 1;
        // no strings can have 0 matches, otherwise map is disconnected
        else if($num_matches == 0)
            return false;
        
        $total_matches += $num_matches;
    }
    
    $total_matches /= 2;
    if($num_indices_with_single_match > 2 || $total_matches < $end)
        return false;
    // **************************************************************
    

    // DFS through array since all other cases have been exhausted
    // **************************************************************
    $max_in_index_map = array();
    $indices_in_index_map = array();
    for($i = 0; $i < $end_array; $i++)
    {
        $indices_in_index_map[$i] = 0;
        $max_in_index_map[$i] = count($index_map[$i]);
    }

    for($i = 0; $i < $end_array; $i++)
    {
        $indices_in_index_map = array();
        foreach($indices_in_index_map as $member)
            $member = 0;

        $current_index = $i;
        $indices_already_visited = array();
        $trail_length = 0;
        
        while($trail_length < $end)
        {            
            // finding which indices are okay to visit
            if($indices_in_index_map[$current_index] >= $max_in_index_map[$current_index])
            {
                // recurse back up a node here
                if($current_index != $i)
                {
                    $indices_in_index_map[$current_index] = 0;
                    $current_index = array_pop($indices_already_visited);
                    $trail_length -= 1;
                }
                // if $current_index == $i, we have exhausted all options
                // can't recurse anymore
                else
                    break;
            }
            else
            {
                $indices_in_index_map[$current_index] += 1;

                // if we haven't visited that index, move to next index
                if(exist_elem_in_array($index_map[$current_index][$indices_in_index_map[$current_index]-1], $indices_already_visited) == false)
                {
                    $trail_length += 1;
                    $indices_already_visited[] = $current_index;
                    $current_index = $index_map[$current_index][$indices_in_index_map[$current_index]-1];
                }
            }
        }
        
        if($trail_length >= $end)
            return true;
    }
    // **************************************************************
    
    return false;
}