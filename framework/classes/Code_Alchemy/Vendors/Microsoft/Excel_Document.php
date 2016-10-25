<?php
/**
 * Created by PhpStorm.
 * User: dgreenberg
 * Date: 8/30/15
 * Time: 3:45 PM
 */

namespace Code_Alchemy\Vendors\Microsoft;



use Code_Alchemy\Core\Code_Alchemy_Framework;
use Code_Alchemy\Core\Random_Password;

class Excel_Document {

    /**
     * @var array of Workbook sheets
     */
    private $sheets = [];

    /**
     * @var array of rows to add to Excel document
     */
    private $rows = array();

    /**
     * @var array of column widths
     */
    private $column_widths = array();

    /**
     * @param Excel_Workbook_Sheet $sheet
     */
    public function add_sheet( Excel_Workbook_Sheet $sheet ){

        $this->sheets[] = $sheet;

    }

    /**
     * @param array $row
     * @return $this for chaining
     */
    public function add_row( array $row ){

        if ( count( $this->sheets ) ){

            $index = count($this->sheets) - 1;

            $active_sheet = $this->sheets[$index];

            $active_sheet->add_row( $row );

            $this->sheets[$index] = $active_sheet;

        } else

            $this->rows[] = $row;

        return $this;

    }

    /**
     * @param $file_full_path
     */
    public function save_to_file( $file_full_path ){

        $this->send_to_output(false,$file_full_path);

    }

    /**
     * @param bool|false $is_simulation
     * @param string $file_full_path
     */
    public function send_to_output( $is_simulation = false, $file_full_path = '' ){

        /** Include PHPExcel_IOFactory */
        //require_once Code_Alchemy_Framework::instance()->webroot()."/phpexcel/Classes/PHPExcel/IOFactory.php";


        $excel = new \PHPExcel();

        $excel->getProperties()->setCreator("Alquemedia SAS")
            ->setLastModifiedBy("Alquemedia SAS")
            ->setTitle("Excel Document")
            ->setSubject("Excel Document")
            ->setDescription("")
            ->setKeywords("")
            ->setCategory("");

        // Initialize Row number
        $row_number = 1;

        if ( count( $this->sheets )){

            foreach ( $this->sheets as $sheet )

                $this->render_sheet( $sheet, $excel );
        }
        // Foreach Row
        foreach ( $this->rows as $row ){

            // Initialize Column
            $column = 'A';


            // Foreach column
            foreach ( $row as $cell_value ){

                $cell_identity = $column.$row_number;

                $excel->setActiveSheetIndex(0)

                    ->setCellValue($cell_identity, $cell_value);

                $this->column_widths[$column] = 1.5 * max(strlen( $cell_value ),@$this->column_widths[$column]);

                $excel->getActiveSheet()->getStyle($cell_identity)

                    ->getAlignment()->applyFromArray(

                        array(

                            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                            'rotation'   => 0,
                            'wrap'       => true
                        )
                    );

                // Advance column
                $column++;

            }

            $excel->getActiveSheet()->getRowDimension($row_number)->setRowHeight(

                $this->get_row_height($row)

                //100

                );

            $row_number++;

        }

        // Set column widths
        $sheet = $excel->getActiveSheet();

        foreach ( $this->column_widths as $column => $width ){

            $width = $width > 30 ? 30: $width;

            $sheet->getColumnDimension($column)->setWidth($width);

        }

        $excel->getActiveSheet()->getStyle("A1:ZZ100")->getFont()->setSize(10);

        if ( ! $is_simulation ){

            $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

            // We'll be outputting an excel file
            header('Content-type: application/vnd.ms-excel');

            // It will be called file.xls
            header('Content-Disposition: attachment; filename="'.new Random_Password(10).'.xls"');

            // Write file to the browser
            $objWriter->save( $file_full_path? $file_full_path: 'php://output');



        }


    }

    private function render_sheet( Excel_Workbook_Sheet $sheet, \PHPExcel &$excel ){

        static $sheet_num = 0;

        $excel->addSheet( new \PHPExcel_Worksheet(null,substr($sheet->name(),0,30)));

        $row_number = 1;

        $excel->setActiveSheetIndex($sheet_num++);

        // Foreach Row
        foreach ( $sheet->rows() as $row ){

            // Initialize Column
            $column = 'A';


            // Foreach column
            foreach ( $row as $cell_value ){

                $cell_identity = $column.$row_number;

                $excel->getActiveSheet()

                    ->setCellValue($cell_identity, $cell_value);

                $this->column_widths[$column] = 1.5 * max(strlen( $cell_value ),@$this->column_widths[$column]);

                $excel->getActiveSheet()->getStyle($cell_identity)

                    ->getAlignment()->applyFromArray(

                        array(

                            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            'vertical'   => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                            'rotation'   => 0,
                            'wrap'       => true
                        )
                    );

                // Advance column
                $column++;

            }

            $excel->getActiveSheet()->getRowDimension($row_number)->setRowHeight(

                $this->get_row_height($row)

            //100

            );

            $row_number++;

        }


    }

    /**
     * @param array $row
     * @return int|mixed
     */
    private function get_row_height( array $row ){

        $height = 50;

        foreach ( $row as $element ){

            if ( ! $element ) break;

            $lines = max(1,substr_count($element,"\r\n"));

            $height = max($height,20*$lines);

        }

        return $height;

    }

}