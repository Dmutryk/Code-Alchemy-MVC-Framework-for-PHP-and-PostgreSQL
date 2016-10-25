<?php


namespace Code_Alchemy\Components;


use Code_Alchemy\helpers\Smart_File_Copier;

class Smart_Copy_Bundle {

    /**
     * Create a smart copy bundle
     * @param $source_directory
     * @param $destination_directory
     * @param $filenames
     * @param $text_replacements
     */
    public function __construct(
        $source_directory,
        $destination_directory,
        $filenames,
        $text_replacements
    ){

        // go ahead and smart copy each one
        foreach ( $filenames as $filename ){

            $copier = new Smart_File_Copier("$source_directory/$filename","$destination_directory/$filename",
            $text_replacements,true);

            $copier->copy();
        }


    }

}