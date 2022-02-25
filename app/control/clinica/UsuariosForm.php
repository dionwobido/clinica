<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Validator\TRequiredValidator;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Wrapper\BootstrapFormBuilder;

class UsuariosForm extends TPage
{
    protected $form;

    use \Adianti\Base\AdiantiStandardFormTrait;

    function __construct()
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');
        $this->setAfterSaveAction(new TAction(['UsuariosList', 'onReload'], ['register_state' => 'true']) );

        $this->setDatabase('db_clinica');
        $this->setActiveRecord('Usuarios');

        $this->form = new BootstrapFormBuilder('form_Usuarios');
        $this->form->setFormTitle('Usuarios');
        $this->form->setClientValidation(true);
        $this->form->setColumnClasses(2, ['col-sm-5 col-lg-4', 'col-sm-7 col-lg-8']);

        $id = new TEntry('id');
        $ativo = new TEntry('ativo');
        $email = new TEntry('email');
        $senha = new TEntry('senha');
        $codigo_verificador = new TEntry('codigo_verificador');

        $this->form->addFields([ new TLabel('Id')], [$id]);
        $this->form->addFields([ new TLabel('Ativo')], [$ativo]);
        $this->form->addFields([ new TLabel('Email')], [$email]);
        $this->form->addFields([ new TLabel('Senha')], [$senha]);
        $this->form->addFields([ new TLabel('Código Verificador')], [$codigo_verificador]);

        //$uf->addValidation('Uf', new TRequiredValidator);
        $ativo->addValidation('Ativo', new TRequiredValidator);
        $email->addValidation('Email', new TRequiredValidator);
        $senha->addValidation('senha', new TRequiredValidator);

        $id->setSize('100%');
        $ativo->setSize('100%');
        $email->setSize('100%');
        $senha->setSize('100%');
        $codigo_verificador->setSize('100%');

        $id->setEditable(FALSE);

        $btn = $this->form->addAction(_t('Save'), new TAction([$this, 'onSave']), 'fa:save' );
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addActionLink(_t('New'), new TAction([$this, 'onEdit']), 'fa:eraser red');

        $this->form->addHeaderActionLink(_t('Close'), new TAction([$this, 'onClose']), 'fa:times red');

        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add($this->form);

        parent::add($container);

    }

    public static function onClose($param)
    {
        TScript::create("Template.closeRightPanel()");
    }
}