<?php


class WriterController extends AppController
{
    public static function read_all()
    {
        self::render(
            'writer.twig',
            array(
            "tags"=>Tags::get_tags(),
            "categories"=>Categories::get_categories()
        )
        );
    }
}
