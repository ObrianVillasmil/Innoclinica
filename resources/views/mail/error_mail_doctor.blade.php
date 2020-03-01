<style>
    .alert-danger{
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    .alert {
        position: relative;
        padding: .75rem 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: .25rem;
    }
</style>

<div class="alert alert-danger" role="alert" style="margin: 0">
     Se esta solicitando un tratamiento con el doctor tratante {{explode(")", $doctor)[1]}} y no tiene un correo configurado para notificarlo.!
</div>