
<style>
    .alert-info{
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }
    .alert {
        position: relative;
        padding: .75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: .25rem;
    }
</style>

<div class="alert alert-info" role="alert">
 {{'Su paciente '. getParty($partyId)->person->first_name .' '.getParty($partyId)->person->last_name  .'
a solicitado un tratamiento en ('.getConfiguracionEmpresa()->nombre_empresa.'contrataciones'}}
>>>>>>> b33f1bba520636fe4938396469f8d02c7ff1f642
</div>