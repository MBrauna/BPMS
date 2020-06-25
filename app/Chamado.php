<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class Chamado extends Model
    {
        protected $table        =   'chamado';
        protected $primaryKey   =   'id_chamado';
        public $timestamps      =   false;
    } // class Chamado extends Model { ... }
