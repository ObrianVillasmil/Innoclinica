<style>
    .btn{
        font-weight: 400;
        border: 1px solid transparent;
        padding: 0.45rem 0.75rem;
        font-size: 0.9rem;
        line-height: 1.5;
        border-radius: 0;
        -webkit-transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
    }
</style>

<div style="width: 100%;margin-top:20px">
    <div style="width: 50%;margin: 0 auto;">
        <div style="background: #2c3e50;width: 100%;height: 150px">
            <div style="padding: 28px;width: 20%;float: left">
              <img src="{{asset('imagenes/img_user.png')}}" style="width: 100px;">
            </div>
            <div style="padding: 28px;width: 80%;">
                <h1 style="color: #fff;margin-bottom: 0px;margin-top: 15px ">Gracias por preferirnos</h1>
                <h2 style="color: #fff;margin-top: 0px">Ya casi terminamos!</h2>
            </div>
        </div>
        <div style="text-align: center;margin: 40px 0px">
            <h1>Por favor confirma tu suscripción</h1>
        </div>
        <div style="text-align: center;margin: 40px 0px">
            <a href="{{url($url."/autenticar/".$party_id."/".$token )}}" style="text-decoration: none;width: 80%;background: #27ae60;color:#fff;font-weight: 400;border: 1px solid transparent;padding: 0.75rem 100px;font-size: 1rem;line-height: 1.5;border-radius: 0;">
                Haga clic para confirmar su cuenta
            </a>
        </div>
        <div style="text-align: center;margin: 40px 0px">
            <h3>Si usted considera que ha recibido este mail de forma errónea por favor elimínelo</h3>
        </div>
        <hr />
        <div style="padding: 28px;text-align: center">
            <a class="btn" target="_blank" href="http://www.innofarm.com.ec">
                <img src="{{asset('imagenes/img_internet.png')}}" style="width: 50px;">
            </a>
        </div>
        <div style="text-align: center">
            <a target="_blank" href="http://www.innofarm.com.ec">
                www.innofarm.com.ec
            </a>
        </div>

    </div>
</div>