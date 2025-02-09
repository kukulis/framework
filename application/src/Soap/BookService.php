<?php

namespace App\Soap;

use App\DTO\Book;

class BookService
{
    private $_books = NULL;

    public function __construct(){
        $this->_books  = [
            ['id'=>'5409' , 'name'=>'Programming for Dummies','year'=>2011,'price'=>'12.09'],
            ['id'=>'2311','name'=>'Project Management 101','year'=>2017,'price'=>'20.09'],
            ['id'=>'98777','name'=>'Rust Development','year'=>2020,'price'=>'32.09'],
        ];
    }


    /**
     * @soap
     * @param int $id
     * @return string
     */
    public function bookYear(int $id): string {

        $bookYear = "";
        foreach($this->_books as $bk){
            if($bk['id']==$id)
                return $bk['year']; // book found
        }

        return $bookYear; // book not found
    }

    /**
     * @soap
     * @param \App\DTO\Book $book
     * @return string
     */
    public function bookDetails($book): string {
        foreach($this->_books as $bk){
            if($bk['name']==$book->name) {
                return json_encode($bk);
//                return $bk;
            }
        }
        return ""; // book not found
    }

}