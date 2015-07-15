<?php

namespace ProcessMaker\BusinessModel;

class ProcessMap
{
    private $running_case;
    private $diagram;

    public function __construct()
    {
    }

    public function get_image($schema, $schemaStatus, $output = 'file')
    {
        if (isset($schema)) {
            $arrActivity = array();
            foreach ($schemaStatus as $task) {
                $arrActivity[$task['tas_uid']] = $task['status'];
            }
            $this->running_case = array(
                'bpmnActivity' => $arrActivity,
            );

            //GET DIAGRAMS
            $diagrams = $this->get_project_diagrams($schema['diagrams']);
            foreach ($diagrams as $diagram) {
                $files = $this->diagram_to_png($diagram);
            }
        }
        return $files;
    }

    /**
     * Function to retrieve shapes of diagrams
     * @param $prj_id
     */
    private function get_project_diagrams($diagram)
    {
        $this->diagram = $diagram;
        if (isset($diagram)) {
            $response = array();
            foreach ($diagram as $row) {
                $tmp = new \stdClass();
                $tmp->activities = $row['activities'];
                $tmp->events = $row['events'];
                $tmp->gateways = $row['gateways'];
                $tmp->artifacts = $row['artifacts'];
                $tmp->flows = $row['flows'];
                $tmp->datas = $row['data'];
                $tmp->participants = $row['participants'];
                $tmp->laneset = $row['laneset'];
                $tmp->lanes = $row['lanes'];
                $response[] = $tmp;
            }
            return $response;
        }
    }

    private function diagram_to_png($diagram, $prj_name = '')
    {
        $serialize_data = serialize($diagram);
        $data = unserialize($serialize_data);
        $png_data = $this->convert_png_array($data);
        //TODO: avoid hardcoded
        $sprite_filename = PATH_HTML . 'lib/img/mafe_sprite.png';
        //TODO: avoid hardcoded
        $sprite_filename_bw = PATH_HTML . 'lib/img/mafe_sprite.png';
        $image_sprite = imagecreatefrompng($sprite_filename);
        $image_sprite_bw = imagecreatefrompng($sprite_filename_bw);
        $sprite_map = $this->load_sprite_coords();

        $image = $this->allocate_diagram_image($png_data, $sprite_map, $image_sprite, $image_sprite_bw);

        return $image;
    }

    private function convert_png_array($data)
    {
        $pngArray = array();

        foreach ($data->participants as $participants) {
            $tmpData = array();
            $tmpData[0] = 'bpmnParticipant';
            $tmpData[1] = $participants['bou_x'];
            $tmpData[2] = $participants['bou_y'];
            $tmpData[3] = $participants['bou_width'];
            $tmpData[4] = $participants['bou_height'];
            $tmpData[5] = "";
            $tmpData[6] = $participants['par_name'];
            $tmpData[7] = "";
            $tmpData[8] = $participants['par_uid'];
            $pngArray[] = $tmpData;
        }

        foreach ($data->laneset as $laneset) {
            $tmpData = array();
            $tmpData[0] = 'bpmnPool';
            $tmpData[1] = $laneset['bou_x'];
            $tmpData[2] = $laneset['bou_y'];
            $tmpData[3] = $laneset['bou_width'];
            $tmpData[4] = $laneset['bou_height'];
            $tmpData[5] = $laneset['dat_type'];
            $tmpData[6] = $laneset['lns_name'];
            $tmpData[7] = "";
            $tmpData[8] = $laneset['lns_uid'];
            $pngArray[] = $tmpData;
        }

        foreach ($data->lanes as $lanes) {
            $tmpData = array();
            $tmpData[0] = 'bpmnLane';
            $tmpData[1] = $lanes['bou_x'];
            $tmpData[2] = $lanes['bou_y'];
            $tmpData[3] = $lanes['bou_width'];
            $tmpData[4] = $lanes['bou_height'];
            $tmpData[5] = "";
            $tmpData[6] = $lanes['lan_name'];
            $tmpData[7] = "";
            $tmpData[8] = $lanes['lan_uid'];
            $tmpData[9] = "";

            $tmpData[10] = $lanes['bou_container'];
            $tmpData[11] = $lanes['bou_element'];
            $pngArray[] = $tmpData;
        }

        foreach ($data->activities as $activity) {
            $tmpData = array();
            $tmpData[0] = 'bpmnActivity';
            $tmpData[1] = $activity['bou_x'];
            $tmpData[2] = $activity['bou_y'];
            $tmpData[3] = $activity['bou_width'];
            $tmpData[4] = $activity['bou_height'];
            $tmpData[5] = $activity['act_type'];
            $tmpData[6] = $activity['act_name'];
            $tmpData[7] = $activity['act_task_type'] . '_' . $activity['act_loop_type'] . '_' . $activity['act_is_adhoc'] . '_' . $activity['act_is_collapsed'];
            $tmpData[8] = $activity['act_uid'];
            $tmpData[9] = $activity['act_script_type'];

            $tmpData[10] = $activity['bou_container'];
            $tmpData[11] = $activity['bou_element'];

            $pngArray[] = $tmpData;
        }

        foreach ($data->events as $event) {
            $tmpData = array();
            $tmpData[0] = 'bpmnEvent';
            $tmpData[1] = $event['bou_x'];
            $tmpData[2] = $event['bou_y'];
            $tmpData[3] = $event['bou_width'];
            $tmpData[4] = $event['bou_height'];
            if ($event['evn_type'] == 'BOUNDARY') {
                $tmpData[5] = $event['evn_is_interrupting'] . '_INTERMEDIATE_EVENT';
            } else {
                $tmpData[5] = $event['evn_is_interrupting'] . '_' . $event['evn_type'] . '_EVENT';
            }
            $tmpData[6] = $event['evn_name'];
            if ($event['evn_type'] == 'BOUNDARY') {
                $tmpData[7] = 'INTERMEDIATE_' . $event['evn_marker'] . '_' . $event['evn_behavior'];
            } else if ($event['evn_type'] == 'INTERMEDIATE') {
                if ($event['evn_marker'] == 'EMPTY') {
                    $tmpData[7] = 'EMPTY';
                } else {
                    if ($event['evn_behavior'] != '') {
                        $tmpData[7] = $event['evn_type'] . '_' . $event['evn_marker'] . '_' . $event['evn_behavior'];
                    } else {
                        $tmpData[7] = $event['evn_type'] . '_' . $event['evn_marker'];
                    }
                }
            } else {
                if ($event['evn_marker'] == 'EMPTY') {
                    $tmpData[7] = 'EMPTY';
                } else {
                    if ($event['evn_message'] != '') {
                        $tmpData[7] = $event['evn_type'] . '_' . $event['evn_marker'] . '_' . $event['evn_message'];
                    } else {
                        $tmpData[7] = $event['evn_type'] . '_' . $event['evn_marker'];
                    }
                }
            }
            $tmpData[8] = $event['evn_uid'];
            $tmpData[9] = "";

            $tmpData[10] = $event['bou_container'];
            $tmpData[11] = $event['bou_element'];
            $pngArray[] = $tmpData;
        }

        foreach ($data->gateways as $gateway) {
            $tmpData = array();
            $tmpData[0] = 'bpmnGateway';
            $tmpData[1] = $gateway['bou_x'];
            $tmpData[2] = $gateway['bou_y'];
            $tmpData[3] = $gateway['bou_width'];
            $tmpData[4] = $gateway['bou_height'];
            $tmpData[5] = $gateway['gat_type'] . '_GATEWAY';
            $tmpData[6] = $gateway['gat_name'];
            $tmpData[7] = '';
            $tmpData[8] = $gateway['gat_uid'];
            $tmpData[9] = '';
            $tmpData[10] = $gateway['bou_container'];
            $tmpData[11] = $gateway['bou_element'];
            $pngArray[] = $tmpData;
        }

        foreach ($data->artifacts as $artifact) {
            $tmpData = array();
            $tmpData[0] = 'bpmnArtifact';
            $tmpData[1] = $artifact['bou_x'];
            $tmpData[2] = $artifact['bou_y'];
            $tmpData[3] = $artifact['bou_width'];
            $tmpData[4] = $artifact['bou_height'];
            $tmpData[5] = $artifact['art_name'];
            $tmpData[6] = $artifact['art_type'];
            $tmpData[7] = '';
            $tmpData[8] = $artifact['art_uid'];
            $tmpData[9] = '';
            $tmpData[10] = $artifact['bou_container'];
            $tmpData[11] = $artifact['bou_element'];
            $pngArray[] = $tmpData;
        }

        foreach ($data->flows as $flow) {
            $tmpData = array();
            $tmpData[0] = 'bpmnFlow';
            $tmpData[1] = $flow['flo_name'];
            $tmpData[2] = $flow['flo_type'];
            $tmpData[3] = $flow['flo_element_origin_type'];
            $tmpData[4] = "";//$flow['flo_element_origin_port'];
            $tmpData[5] = $flow['flo_element_dest_type'];
            $tmpData[6] = "";//$flow['flo_element_dest_port'];
            $tmpData[7] = $flow['flo_element_origin'];
            $tmpData[8] = $flow['flo_element_dest'];
            $tmpData[9] = $flow['flo_state'];
            $pngArray[] = $tmpData;
        }

        foreach ($data->datas as $data) {
            $tmpData = array();
            $tmpData[0] = 'bpmnData';
            $tmpData[1] = $data['bou_x'];
            $tmpData[2] = $data['bou_y'];
            $tmpData[3] = $data['bou_width'];
            $tmpData[4] = $data['bou_height'];
            $tmpData[5] = $data['dat_type'];
            $tmpData[6] = $data['dat_name'];
            $tmpData[7] = "";
            $tmpData[8] = $data['dat_uid'];
            $tmpData[9] = '';
            $tmpData[10] = $data['bou_container'];
            $tmpData[11] = $data['bou_element'];
            $pngArray[] = $tmpData;
        }

        return $pngArray;
    }

    private function load_sprite_coords()
    {
        $xMap = array();
        $xMap['1_START_EVENT'] = array(0, 4759);
        $xMap['START_MESSAGECATCH_LEAD'] = array(0, 9371);
        $xMap['START_TIMER_LEAD'] = array(0, 8872);
        $xMap['START_CONDITIONAL_LEAD'] = array(0, 9180);
        $xMap['START_SIGNALCATCH_LEAD'] = array(0, 8905);
        $xMap['INTERMEDIATE_MESSAGETHROW_THROW'] = array(0, 8987);
//        $xMap['INTERMEDIATE_LINKTHROW_THROW'] = array(0, 4887);
//        $xMap['INTERMEDIATE_COMPENSATIONTHROW_THROW'] = array(0, 4260);
        $xMap['INTERMEDIATE_SIGNALTHROW_THROW'] = array(0, 9338);
        $xMap['INTERMEDIATE_MESSAGECATCH_CATCH'] = array(0, 9213);
        $xMap['INTERMEDIATE_TIMER_CATCH'] = array(0, 8704);
        $xMap['INTERMEDIATE_CONDITIONAL_CATCH'] = array(0, 9053);
//        $xMap['INTERMEDIATE_LINKCATCH_CATCH'] = array(0, 4648);
        $xMap['INTERMEDIATE_SIGNALCATCH_CATCH'] = array(0, 9246);

        $xMap['1_END_EVENT'] = array(0, 4832);
        $xMap['END_MESSAGETHROW'] = array(0, 9486);
        $xMap['END_ERRORTHROW'] = array(0, 9545);
        $xMap['END_CANCELTHROW'] = array(0, 5125);
        $xMap['END_COMPENSATIONTHROW'] = array(0, 5473);
        $xMap['END_SIGNALTHROW'] = array(0, 9657);
        $xMap['END_TERMINATETHROW'] = array(0, 9609);

        $xMap['EXCLUSIVE_GATEWAY'] = array(0, 2624);
        $xMap['PARALLEL_GATEWAY'] = array(0, 3301);
        $xMap['INCLUSIVE_GATEWAY'] = array(0, 2369);
//        $xMap['EVENTBASED_GATEWAY'] = array(0, 2753);
//        $xMap['COMPLEX_GATEWAY'] = array(0, 4394);

        $xMap['TASK_SENDTASK'] = array(0, 10468);
        $xMap['TASK_RECEIVETASK'] = array(0, 10219);
        $xMap['TASK_USERTASK'] = array(0, 4453);
        $xMap['TASK_SERVICETASK'] = array(0, 8439);
        $xMap['TASK_SCRIPTTASK'] = array(0, 8851);
        $xMap['TASK_MANUALTASK'] = array(0, 9777);
        $xMap['TASK_BUSINESSRULE'] = array(0, 10561);
        $xMap['LOOP_LOOP'] = array(0, 5654);
        $xMap['LOOP_PARALLEL'] = array(0, 7108);
        $xMap['LOOP_SEQUENTIAL'] = array(0, 7036);

        $xMap['DATAOBJECT'] = array(0, 5401);
        $xMap['DATAINPUT'] = array(0, 5791);
        $xMap['DATAOUTPUT'] = array(0, 6071);
        $xMap['DATASTORE'] = array(0, 3037);


        $xMap['arrow_target_right'] = array(0, 6727);
        $xMap['arrow_target_left'] = array(0, 6774);
        $xMap['arrow_target_top'] = array(0, 6819);
        $xMap['arrow_target_bottom'] = array(0, 6852);

        $xMap['arrow_conditional_source_right'] = array(0, 99);
        $xMap['arrow_conditional_source_left'] = array(0, 99);
        $xMap['arrow_conditional_source_top'] = array(0, 111);
        $xMap['arrow_conditional_source_bottom'] = array(0, 111);

        $xMap['arrow_default_source_right'] = array(0, 6893);
        $xMap['arrow_default_source_left'] = array(0, 6910);
        $xMap['arrow_default_source_top'] = array(0, 6863);
        $xMap['arrow_default_source_bottom'] = array(0, 6882);

        $xMap['text_now'] = array(0, 0);
        $xMap['icon_terminated'] = array(0, 10);
        return $xMap;
    }

    private function allocate_diagram_image(array $pngData, $xSpriteMap, $imgSprite, $imgSpriteBW = '')
    {
        $font = PATH_HTML .'lib/fonts/Chivo/Chivo-Regular.ttf';
        $minX = 10000;
        $minY = 10000;
        $maxW = 0;
        $maxH = 0;
        $border = 40;

        foreach ($pngData as $coords) {
            if ($coords[0] !== 'bpmnFlow') {
                if ($minX > $coords[1]) {
                    $minX = $coords[1];
                }
                if ($minY > $coords[2]) {
                    $minY = $coords[2];
                }
                if ($maxW < ($coords[1] + $coords[3])) {
                    $maxW = $coords[1] + $coords[3];
                }
                if ($maxH < ($coords[2] + $coords[4])) {
                    $maxH = $coords[2] + $coords[4];
                }
            }
        }

        $x1 = $minX - $border;
        $y1 = $minY - $border;
        $x2 = $maxW + $border;
        $y2 = $maxH + $border;
        $cWidth = $x2 - $x1;
        $cHeight = $y2 - $y1;

        if ($cWidth < 0 && $cHeight < 0) {
            $cWidth = 100;
            $cHeight = 100;
        }

        $img = imagecreatetruecolor($cWidth, $cHeight);

        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);
        $groupColor = imagecolorallocate($img, 153, 94, 6);
        $gray = imagecolorallocate($img, 0xC0, 0xC0, 0xC0);
        $aNotSupportedColor = imagecolorallocate($img, 59, 71, 83);
        $aNotSupportedFillColor = $white;

        imagefill($img,0,0,$white);
        foreach ($pngData as $figure) {
            $shape_running = $this->get_shape_process($figure[8], $figure[0], $img);
            $shape_image = $imgSprite ;
            $aTaskColor = isset($shape_running->colors['color']) ? $shape_running->colors['color'] : imagecolorallocate($img, 59, 71, 83);
            $aTaskFillColor = isset($shape_running->colors['fillcolor']) ? $shape_running->colors['fillcolor'] : imagecolorallocate($img, 255, 255, 255);
            switch ($figure[0]) {
                case 'bpmnParticipant':
                case 'bpmnPool':
                    $X1 = $figure[1] - $x1;
                    $Y1 = $figure[2] - $y1;
                    $X2 = $X1 + $figure[3];
                    $Y2 = $Y1 + $figure[4];
                    $points = array($X1 + 3, $Y1, $X2 - 3, $Y1, $X2, $Y1 + 3, $X2, $Y2 - 3, $X2 - 3, $Y2, $X1 + 3, $Y2, $X1, $Y2 - 3, $X1, $Y1 + 3);
                    $borderColor = $aNotSupportedColor;
                    $fillColor = $aNotSupportedFillColor;
                    imagesetthickness($img, 3);

                    imagefilledpolygon($img, $points, 8, $fillColor);
                    imagepolygon($img, $points, 8, $borderColor);
                    imageline ( $img , $X1+40 , $Y1 , $X1+40 , $Y2 , $aTaskColor );
                    //Print Text
                    if (isset($figure[9]) && $figure[9] != '') {
                        $tt = explode('_', $figure[7]);
                        $this->print_text($img, $figure[6], 10, 90, $black, $font, $X1, $Y1, $X2, $Y2, $figure[0], $tt[0]);
                    } else {
                        $this->print_text($img, $figure[6], 10, 90, $black, $font, $X1, $Y1, $X2, $Y2, $figure[0], $figure[5]);
                    }
                    break;
                case 'bpmnLane':
                    $newPoints = $this->getNewPoints($figure[11],$figure[10]);

                    $X1 = $figure[1] - $x1 + $newPoints[0];
                    $Y1 = $figure[2] - $y1 + $newPoints[1];
                    $X2 = $X1 + $figure[3];
                    $Y2 = $Y1 + $figure[4];
                    $points = array($X1 + 3, $Y1, $X2 - 3, $Y1, $X2, $Y1 + 3, $X2, $Y2 - 3, $X2 - 3, $Y2, $X1 + 3, $Y2, $X1, $Y2 - 3, $X1, $Y1 + 3);
                    $borderColor = $aNotSupportedColor;
                    $fillColor = $aNotSupportedFillColor;
                    imagesetthickness($img, 3);

                    imagefilledpolygon($img, $points, 8, $fillColor);
                    imagepolygon($img, $points, 8, $borderColor);

                    if (isset($figure[9]) && $figure[9] != '') {
                        $tt = explode('_', $figure[7]);
                        $this->print_text($img, $figure[6], 10, 90, $black, $font, $X1, $Y1, $X2, $Y2, $figure[0], $tt[0]);
                    } else {
                        $this->print_text($img, $figure[6], 10, 90, $black, $font, $X1, $Y1, $X2, $Y2, $figure[0], $figure[5]);
                    }
                    break;
                case 'bpmnActivity':
                    $newPoints = $this->getNewPoints($figure[11],$figure[10]);
                    $X1 = $figure[1] - $x1 + $newPoints[0];
                    $Y1 = $figure[2] - $y1 + $newPoints[1];
                    $X2 = $X1 + $figure[3];
                    $Y2 = $Y1 + $figure[4];
                    $properties = explode('_', $figure[7]);
                    $points = array($X1 + 3, $Y1, $X2 - 3, $Y1, $X2, $Y1 + 3, $X2, $Y2 - 3, $X2 - 3, $Y2, $X1 + 3, $Y2, $X1, $Y2 - 3, $X1, $Y1 + 3);
                    $points2 = array($X1 + 5, $Y1 + 2, $X2 - 5, $Y1 + 2, $X2 - 2, $Y1 + 5, $X2 - 2, $Y2 - 5, $X2 - 5, $Y2 - 2, $X1 + 5, $Y2 - 2, $X1 + 2, $Y2 - 5, $X1 + 2, $Y1 + 5);
                    switch ($figure[5]) {
                        case 'TASK':
                            $borderColor = $aTaskColor;
                            $fillColor = $aTaskFillColor;
                            imagesetthickness($img, 2);
                            break;
                        default:
                            $borderColor = $aNotSupportedColor;
                            $fillColor = $aNotSupportedFillColor;
                            imagesetthickness($img, 4);
                    }
                    //CURRENT CASE
                    if ($shape_running->running) {
                        $points_active = array($X1 + 3, $Y1, $X2 - 3, $Y1, $X2, $Y1 + 3, $X2, $Y2 - 3, $X2 - 3, $Y2, $X1 + 3, $Y2, $X1, $Y2 - 3, $X1, $Y1 + 3);
                            imagefilledpolygon($img, $points, 8, $fillColor);
                            imagepolygon($img, $points_active, 8, $borderColor);
                    } else {
                        imagefilledpolygon($img, $points, 8, $fillColor);
                        imagepolygon($img, $points, 8, $borderColor);
                    }
                    //Task Type
                    if ($figure[5] == 'TASK' || $figure[5] == 'TASKCALLACTIVITY') {
                        if (isset($figure[9]) && $figure[9] != '') {
                            $css = 'scripttask_' . strtolower($figure[9]);
                            $spriteCoords = $xSpriteMap[$css];
                            imagecopy($img, $shape_image, $figure[1] - $x1 - 2 + $newPoints[0], $figure[2] - $y1 - 2 + $newPoints[1], $spriteCoords[0], $spriteCoords[1], 39, 39);
                        } elseif ($properties[0] != "EMPTY") {
                            $css = 'TASK_' . strtoupper($properties[0]);
                            $spriteCoords = $xSpriteMap[$css];
                            imagecopy($img, $shape_image, $figure[1] - $x1 + 4 + $newPoints[0], $figure[2] - $y1 + 4 + $newPoints[1], $spriteCoords[0], $spriteCoords[1], 21, 21);
                        }
                    }
                    //Makers
                    if ($figure[5] == 'TASK' && ($properties[1] != 'NONE' && $properties[1] != 'EMPTY')) {
                        $css = 'LOOP_' . strtoupper($properties[1]);
                        $spriteCoords = $xSpriteMap[$css];
                        imagecopy($img, $shape_image, $figure[1] - $x1 + $newPoints[0] + ($figure[3] - 21) / 2, $figure[2] - $y1 + $newPoints[1] + $figure[4] - 23, $spriteCoords[0], $spriteCoords[1], 21, 21);
                    }
                    //Print Text
                    if (isset($figure[9]) && $figure[9] != '') {
                        $tt = explode('_', $figure[7]);
                        $this->print_text($img, $figure[6], 10, 0, $black, $font, $X1, $Y1, $X2, $Y2, $figure[0], $tt[0]);
                    } else {
                        $this->print_text($img, $figure[6], 10, 0, $black, $font, $X1, $Y1, $X2, $Y2, $figure[0], $figure[5]);
                    }
                    break;
                case 'bpmnEvent':
                    $newPoints = $this->getNewPoints($figure[11],$figure[10]);
                    $X1 = $figure[1] - $x1 + $newPoints[0];
                    $Y1 = $figure[2] - $y1 + $figure[4] - 10 + $newPoints[1];
                    $X2 = $X1 + $figure[3];
                    $Y2 = $Y1 + $figure[4] + 5;
                    $css = $figure[5];
                    $marker = $figure[7];
                    $spriteCoords = ($marker != 'EMPTY')?$xSpriteMap[$marker]:$xSpriteMap[$css];
                    $mk = explode('_', $figure[7]);
                    //CURRENT CASE
                    imagecopy($img, $shape_image, $figure[1] - $x1 + $newPoints[0], $figure[2] - $y1 + $newPoints[1], $spriteCoords[0], $spriteCoords[1], $figure[3], $figure[4]);

                    if ($marker != 'EMPTY') {
                        //END_CANCELTHROW???
                        if (isset($xSpriteMap[$marker])) {
                            $spriteCoords2 = $xSpriteMap[$marker];
                            if (!($shape_running->running && $mk[1] == 'TIMER')) {
                                imagecopy($img, $shape_image, $figure[1] - $x1 + $newPoints[0], $figure[2] - $y1 + $newPoints[1], $spriteCoords2[0], $spriteCoords2[1], $figure[3], $figure[4]);
                            }
                        }
                    }
                    $this->print_text($img, $figure[6], 10, 0, $black, $font, $X1, $Y1, $X2, $Y2, $figure[0]);
                    break;
                case 'bpmnGateway':
                    $newPoints = $this->getNewPoints($figure[11],$figure[10]);
                    $X1 = $figure[1] - $x1 + $newPoints[0];
                    $Y1 = $figure[2] - $y1 + $figure[4] - 10 + $newPoints[1];
                    $X2 = $X1 + $figure[3];
                    $Y2 = $Y1 + $figure[4] + 5;
                    $css = $figure[5];
                    $spriteCoords = $xSpriteMap[$css];
                    imagecopy($img, $shape_image, $figure[1] - $x1 + $newPoints[0], $figure[2] - $y1 + $newPoints[1], $spriteCoords[0], $spriteCoords[1], $figure[3], $figure[4]);
                    $this->print_text($img, $figure[6], 10, 0, $black, $font, $X1, $Y1, $X2, $Y2, $figure[0]);
                    break;
                case 'bpmnArtifact':
                    $newPoints = $this->getNewPoints($figure[11],$figure[10]);
                    $xX1 = $figure[1] - $x1 + $newPoints[0];
                    $xY1 = $figure[2] - $y1 + $newPoints[1];
                    $xX2 = $xX1 + $figure[3];
                    $xY2 = $xY1 + $figure[4];

                    if ($figure[6] == 'GROUP') {
                        imagesetthickness($img, 2);
                        $style = array(
                            $groupColor, $groupColor, $groupColor, $groupColor, $groupColor,
                            $white, $white, $white, $white, $white
                        );
                        imagesetstyle($img, $style);
                        imageline($img, $xX1, $xY1, $xX2, $xY1, IMG_COLOR_STYLED);
                        imageline($img, $xX2, $xY1, $xX2, $xY2, IMG_COLOR_STYLED);
                        imageline($img, $xX2, $xY2, $xX1, $xY2, IMG_COLOR_STYLED);
                        imageline($img, $xX1, $xY2, $xX1, $xY1, IMG_COLOR_STYLED);
                        $this->print_text($img, $figure[5], 10, 0, $black, $font, $xX1, $xY1 - 5, $xX2, $xY2, $figure[0], $figure[5]);
                    }
                    if ($figure[6] == 'TEXT_ANNOTATION') {
                        imagesetthickness($img, 1);
                        imageline($img, $xX1, $xY1, $xX1, $xY2, $black);
                        imageline($img, $xX1, $xY1, $xX1 + 15, $xY1, $black);
                        imageline($img, $xX1, $xY2, $xX1 + 15, $xY2, $black);
                        $this->print_text($img, $figure[5], 10, 0, $black, $font, $xX1, $xY1, $xX2, $xY2, $figure[0], $figure[6]);
                    }
                    break; //this break wasn't here ...
                case 'bpmnData':
                    $newPoints = $this->getNewPoints($figure[11],$figure[10]);
                    $X1 = $figure[1] - $x1 + $newPoints[0];
                    $Y1 = $figure[2] - $y1 + $figure[4] - 10 + $newPoints[1];
                    $X2 = $X1 + $figure[3];
                    $Y2 = $Y1 + $figure[4] + 5;
                    $css = $figure[5];
                    $spriteCoords = $xSpriteMap[$css];
                    imagecopy($img, $shape_image, $figure[1] - $x1 + $newPoints[0], $figure[2] - $y1 + $newPoints[1], $spriteCoords[0], $spriteCoords[1], $figure[3], $figure[4]);
                    $this->print_text($img, $figure[6], 10, 0, $black, $font, $X1, $Y1, $X2, $Y2, $figure[0]);
                    break;
                case 'bpmnFlow':
                    $X1 = $figure[1] - $x1 ;
                    $Y1 = $figure[2] - $y1 ;
                    $X2 = $X1 + $figure[3];
                    $Y2 = $Y1 + $figure[4];
                    imagesetthickness($img, 1);
                    $lines = $figure[9];
                    $shape_o = $this->get_shape_process($figure[7], $figure[3]);
                    $shape_d = $this->get_shape_process($figure[8], $figure[5]);
//                    if ($shape_o->in_flow && $shape_d->in_flow) {
                        $line_color = $black;
                        $shape_image = $imgSprite;
//                    } else {
//                        $line_color = $gray;
//                        $shape_image = $imgSpriteBW;
//                    }
                    foreach ($lines as $key => $segment) {
                        if (isset($lines[$key + 1]) && $lines[$key + 1]['x'] != '' && $lines[$key + 1]['y'] != '') {
                            if ($figure[2] == 'MESSAGE' || $figure[2] == 'ASSOCIATION' || $figure[2] == 'DATAASSOCIATION') {
                                $style = array(
                                    $black, $black, $black, $black,
                                    $white, $white, $white, $white
                                );
                                imagesetstyle($img, $style);
                                imageline($img, $lines[$key]['x'] - $x1, $lines[$key]['y'] - $y1, $lines[$key + 1]['x'] - $x1, $lines[$key + 1]['y'] - $y1, IMG_COLOR_STYLED);
                            } else {
                                imageline($img, $lines[$key]['x'] - $x1, $lines[$key]['y'] - $y1, $lines[$key + 1]['x'] - $x1, $lines[$key + 1]['y'] - $y1, $line_color);
                            }
                            if ((int) ((sizeof($lines) - 1) / 2) == $key) {
                                $this->print_text($img, $figure[1], 10, 0, $black, $font, $lines[$key]['x'] - $x1, $lines[$key]['y'] - $y1, $lines[$key + 1]['x'] - $x1, $lines[$key + 1]['y'] - $y1, $figure[0]);
                            }
                        }
                    }

                    $decorator_width = 11;
                    $decorator_height = 11;
                    //END DECORATOR

                    if ($lines[sizeof($lines) - 1]['x'] == $lines[sizeof($lines) - 2]['x']) {
                        if ($lines[sizeof($lines) - 1]['y'] < $lines[sizeof($lines) - 2]['y']) {
                            $spriteCoords = $xSpriteMap['arrow_target_bottom'];
                            imagecopy($img, $shape_image, $lines[sizeof($lines) - 1]['x'] - (int) ($decorator_width / 2) - $x1, $lines[sizeof($lines) - 1]['y'] - $y1, $spriteCoords[0], $spriteCoords[1], $decorator_width, $decorator_height);
                        } else {
                            $spriteCoords = $xSpriteMap['arrow_target_top'];
                            imagecopy($img, $shape_image, $lines[sizeof($lines) - 1]['x'] - (int) ($decorator_width / 2) - $x1, $lines[sizeof($lines) - 1]['y'] - $decorator_height - $y1, $spriteCoords[0], $spriteCoords[1], $decorator_width, $decorator_height);
                        }
                    } elseif (($lines[sizeof($lines) - 1]['y'] == $lines[sizeof($lines) - 2]['y'])) {
                        if ($lines[sizeof($lines) - 1]['x'] < $lines[sizeof($lines) - 2]['x']) {
                            $spriteCoords = $xSpriteMap['arrow_target_right'];
                            imagecopy($img, $shape_image, $lines[sizeof($lines) - 1]['x'] - $x1, $lines[sizeof($lines) - 1]['y'] - (int) ($decorator_height / 2) - $y1, $spriteCoords[0], $spriteCoords[1], $decorator_width, $decorator_height);
                        } else {
                            $spriteCoords = $xSpriteMap['arrow_target_left'];
                            imagecopy($img, $shape_image, $lines[sizeof($lines) - 1]['x'] - $decorator_width - $x1, $lines[sizeof($lines) - 1]['y'] - (int) ($decorator_height / 2) - $y1, $spriteCoords[0], $spriteCoords[1], $decorator_width, $decorator_height);
                        }
                    }

                    //SOURCE DECORATOR
                    if ($figure[2] === 'DEFAULT' OR $figure[2] === 'CONDITIONAL') {
                        if ($figure[2] === 'DEFAULT') {
                            $source_decorator = '_default';
                        } elseif ($figure[2] === 'CONDITIONAL') {
                            $source_decorator = '_conditional';
                        }

                        if ($lines[0]['x'] == $lines[1]['x']) {
                            if ($lines[0]['y'] < $lines[1]['y']) {
                                $spriteCoords = $xSpriteMap['arrow' . $source_decorator . '_source_top'];
                                imagecopy($img, $shape_image, $lines[0]['x'] - (int) ($decorator_width / 2) - $x1, $lines[0]['y'] - $y1, $spriteCoords[0], $spriteCoords[1], $decorator_width, $decorator_height);
                            } else {
                                $spriteCoords = $xSpriteMap['arrow' . $source_decorator . '_source_bottom'];
                                imagecopy($img, $shape_image, $lines[0]['x'] - (int) ($decorator_width / 2) - $x1, $lines[0]['y'] - $decorator_height - $y1, $spriteCoords[0], $spriteCoords[1], $decorator_width, $decorator_height);
                            }
                        } elseif (($lines[0]['y'] == $lines[1]['y'])) {
                            if ($lines[0]['x'] < $lines[1]['x']) {
                                $spriteCoords = $xSpriteMap['arrow' . $source_decorator . '_source_right'];
                                imagecopy($img, $shape_image, $lines[0]['x'] - $x1, $lines[0]['y'] - (int) ($decorator_height / 2) - $y1, $spriteCoords[0], $spriteCoords[1], $decorator_width, $decorator_height);
                            } else {
                                $spriteCoords = $xSpriteMap['arrow' . $source_decorator . '_source_left'];
                                imagecopy($img, $shape_image, $lines[0]['x'] - $decorator_width - $x1, $lines[0]['y'] - (int) ($decorator_height / 2) - $y1, $spriteCoords[0], $spriteCoords[1], $decorator_width, $decorator_height);
                            }
                        }
                    }
                    break;
            }
        }
        return $img;
    }

    private function get_shape_process($id, $shape, $img = null)
    {
        $result = new \stdClass();
        $result->running = false;
        $process_route = $this->running_case;
        if ($shape != 'bpmnFlow') {
            if (isset($process_route[$shape]) && array_key_exists($id, $process_route[$shape])) {
                $result->status = $process_route[$shape][$id];
                $result->colors = $this->get_shape_process_color($process_route[$shape][$id], $img);
                $result->running = true;
            }
        }
        return $result;
    }

    private function get_shape_process_color($status, $img)
    {
        $img = is_null($img) ? imagecreate(10,10):$img;
        $red = imagecolorallocate($img, 189, 10, 23);
        $red_1 = imagecolorallocate($img, 114, 2, 12);
        $orange = imagecolorallocate($img, 197, 119, 1);
        $orange_1 = imagecolorallocate($img, 150, 91, 2);
        $silver = imagecolorallocate($img, 170, 168, 166);
        $silver_1 = imagecolorallocate($img, 111, 109, 108);
        $green = imagecolorallocate($img, 27, 121, 9);
        $green_1 = imagecolorallocate($img, 15, 85, 2);
        $white = imagecolorallocate($img, 59, 71, 83);
        $white_1 = imagecolorallocate($img, 255, 255, 255);

        $result = array();
        switch ($status) {
            case 'TASK_IN_PROGRESS'://red
                $result['fillcolor'] = $red;
                $result['color']     = $red_1;
                break;
            case 'TASK_COMPLETED'://green
                $result['fillcolor'] = $green;
                $result['color']     = $green_1;
                break;
            case 'TASK_PENDING_NOT_EXECUTED'://silver
                $result['fillcolor'] = $silver;
                $result['color']     = $silver_1;
                break;
            case 'TASK_PARALLEL'://orange
                $result['fillcolor'] = $orange;
                $result['color']     = $orange_1;
                break;
            default:
                $result['fillcolor'] = $white;
                $result['color']     = $white_1;
                break;
        }
        return $result;
    }

    private function getNewPoints($idElement, $elementName)
    {
        $defenitions = array(
            'bpmnParticipant' => 'participants',
            'bpmnPool'        => 'laneset',
            'bpmnLane'        => 'lanes',
            'bpmnActivity'    => 'activities',
            'bpmnEvent'       => 'events',
            'bpmnGateway'     => 'gateways',
            'bpmnArtifact'    => 'artifacts',
            'bpmnData'        => 'datas'
        );

        $result = array(0,0);
        $resRec = array(0,0);
        if(isset($defenitions[$elementName])){
            $name = $defenitions[$elementName];
            foreach($this->diagram as $schem){
                $elements = $schem[$name];
                foreach ($elements as $element) {
                    if($element['bou_container'] != "bpmnDiagram"){
                        $resRec = $this->getNewPoints($element['bou_element'],$element['bou_container']);
                    }
                    if($element['lns_uid'] == $idElement || $element['lan_uid'] == $idElement){
                        $result = array($element['bou_x'] + $resRec[0],$element['bou_y'] + $resRec[1]);
                    }
                }
            }
        }

        return $result;
    }

    private function print_text($IMG, $txt, $size, $angle, $color, $font, $x1, $y1, $x2, $y2, $type = '', $stype = '')
    {
        //TODO Create a section to write multi-line text
        $yy = 0;
        switch ($type) {
            case 'bpmnActivity':
            case 'bpmnArtifact':
                if ($stype == 'SCRIPTTASK') {
                    $line = $this->wrap_text($size, $angle, $font, $txt, $x2 + 50 - $x1);
                } else {
                    $line = $this->wrap_text($size, $angle, $font, $txt, $x2 - $x1);
                }
                break;
            case 'bpmnEvent':
                $line = $this->wrap_text($size, $angle, $font, $txt, $x2 + 40 - $x1);
                break;
            case 'bpmnGateway':
                $line = $this->wrap_text($size, $angle, $font, $txt, $x2 + 40 - $x1);
                break;
            case 'bpmnPool':
            case 'bpmnParticipant':
            case 'bpmnLane':
                $line = $this->wrap_text($size, $angle, $font, $txt, $y2 + 40 - $y1);
                break;
            default:
                $line = $this->wrap_text($size, $angle, $font, $txt, $x2 - $x1);
        }
        $h = count($line) * 16;
        foreach ($line as $value) {
            $w = strlen(trim($value))*6;
            $X = ($x1 + ((($x2 - $x1) - $w) / 2)) - 5;
            if ($type == 'bpmnActivity' && $stype == 'TASK') {
                $Y = $y1 + (($y2 - $y1)/2) - floor($h/2) + $yy + 10;
            } else if ($type == 'bpmnArtifact' && $stype == 'TEXT_ANNOTATION') {
                $Y = $y1 + (($y2 - $y1)/2) - floor($h/2) + $yy + 10;
            } else if ($type == 'bpmnActivity' && $stype == 'SCRIPTTASK') {
                $Y = $y2 + $yy + 100;
            } else if ($type == 'bpmnPool' || $type == 'bpmnParticipant' || $type == 'bpmnLane') {
                $X = $x1  + $yy + 25;
                $Y = ($y2 - ((($y2 - $y1) - $w) / 2));
            } else if ($type == 'bpmnFlow') {
                $Y = $y1 + $yy + 15;
            } else {
                $Y = $y1 + $yy + 25;
            }
            imagettftext($IMG, $size, $angle, $X, $Y, $color, $font, $value);
            $yy += 16;
        }
    }

    private function wrap_text($fontSize, $angle, $fontFace, $string, $width)
    {
        $pattern = '[\n|\r|\n\r]';
        $string = preg_replace($pattern, ' ', trim($string));
        $arr = explode(' ', $string);
        $sa = '';
        $sf = array();
        foreach ($arr as $word) {
            $sa_ = $sa;
            $sa .= ' ' . $word;
            $w = strlen(trim($sa))*6;
            if ($w >= $width) {
                $sf[] = $sa_;
                $sa = $word;
            }
        }
        $sf[] = $sa;
        return $sf;
    }
}
