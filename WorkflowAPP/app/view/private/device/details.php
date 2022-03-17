
<div class="grid-container">
    <div class="grid-x grid-padding-x">
        <div class="large-3 medium-2 cell"></div>
        <div class="large-6 medium-8 cell">
            <form action="<?=App::config('url')?>device/action" method="post">
                <fieldset>
                    <legend>
                        <h4>Device details</h4>
                    </legend>

                    <label for="fistname">First name</label>
                        <input type="text" name="firstname" id="firstname" value="<?=$entity->firstname?>">
                    
                    <label for="lastname">Last name</label>
                            <input type="text" name="lastname" id="lastname" value="<?=$entity->lastname?>">

                    <label for="company">Company</label>
                        <input type="text" name="company" id="company" value="<?=$entity->company?>">

                    <label for="phonenum">Phone number</label>
                        <input type="text" name="phonenum" id="phonenum" value="<?=$entity->phonenum?>">

                    <label for="email">E-mail</label>
                        <input type="email" name="email" id="email" value="<?=$entity->email?>">

                    <label for="manufacturer">Manufacturer</label>
                        <input type="text" name="manufacturer" id="manufacturer" value="<?=$entity->manufacturer?>">

                    <label for="model">Model</label>
                        <input type="model" name="model" id="model" value="<?=$entity->model?>">

                    <label for="serialnum">Serial number</label>
                        <input type="serialnum" name="serialnum" id="serialnum" value="<?=$entity->serialnum?>">

                        <input type="hidden" name="device_id" value="<?=$entity->device_id?>">

                    <hr/>
                    <div class="grid-x grid-padding-x">
                        <div class="large-4 medium-4 small-4">
                            <a class="alert button expanded" href="<?=App::config('url')?>device/index"><<<   Abort</a>
                        </div>
                        <div class="large-8 medium-8 small-8">
                            <input type="submit" class="button expanded" value="<?=$action?>">
                        </div>
                    </div>
                    <hr/>
                    <p style="color: red"><?=$message?></p>
                </fieldset>
            </form>
        </div>
        <div class="large-3 medium-2 cell"></div>
    </div>
</div>