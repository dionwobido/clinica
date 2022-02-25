<?php

use Adianti\Control\TAction;
use Adianti\Control\TPage;
use Adianti\Validator\TRequiredValidator;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Form\TDate;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Wrapper\BootstrapFormBuilder;

class PacientesForm extends TPage
{
    protected $form;

    use \Adianti\Base\AdiantiStandardFormTrait;

    function __construct()
    {
        parent::__construct();

        parent::setTargetContainer('adianti_right_panel');
        $this->setAfterSaveAction(new TAction(['PacientesList', 'onReload'], ['register_state' => 'true']) );

        $this->setDatabase('db_clinica');
        $this->setActiveRecord('Pacientes');

        $this->form = new BootstrapFormBuilder('form_Pacientes');
        $this->form->setFormTitle('Pacientes');
        $this->form->setClientValidation(true);
        $this->form->setColumnClasses(2, ['col-sm-5 col-lg-4', 'col-sm-7 col-lg-8']);

        $id = new TEntry('id');
        $data_nascimento = new TDate('data_nascimento');
        $nome = new TEntry('nome');
        $usuario_id = new TEntry('usuario_id','db_clinica', 'Usuario', 'id', 'nome' );

        $this->form->addFields([ new TLabel('Id')], [$id]);
        $this->form->addFields([ new TLabel('Data de Nascimento')], [$data_nascimento]);
        $this->form->addFields([ new TLabel('Nome')], [$nome]);
        $this->form->addFields([ new TLabel('Usuário ID')], [$usuario_id]);

        //$data_inscricao->setExitAction($exit_action);

        $data_nascimento->setMask('dd/mm/yyyy');
        $data_nascimento->setDatabaseMask('yyyy-mm-dd');

        //$uf->addValidation('Uf', new TRequiredValidator);
        $data_nascimento->addValidation('Data de Nascimento', new TRequiredValidator);
        $nome->addValidation('Nome', new TRequiredValidator);
        $usuario_id->addValidation('Usuário ID', new TRequiredValidator);

        $id->setSize('100%');
        $data_nascimento->setSize('100%');
        $nome->setSize('100%');
        $usuario_id->setSize('100%');

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