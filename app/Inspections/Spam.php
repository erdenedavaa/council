<?php


    namespace App\Inspections;


    class Spam
    {
        protected $inspections = [
            InvalidKeywords::class
            // KeyHeldDown::class
        ];

        public function detect($body)
        {
            foreach ($this->inspections as $inspection) {
                app($inspection)->detect($body);
                // create an instance of that (or fetch container)
                // and we call detect method, then pass through body
            }

            return false;
        }

    }
