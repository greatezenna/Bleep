<?php

class CustomError extends Error
{
    
    public function notFound()
    {
        try {
            
            return "NOT FOUND";

        } catch (CustomError $e) {

            return $e->getMessage();

        }
    }
};
