<div class="modal">
    <div class="modal-wrapper" >
        <div class="row">
            <div class="col-xs-3">
                <img id="product_img" src=""/>
            </div>
            <div class="col-xs-9">
                <p class="color_black bolder"id="customer_firstname"></p>
                <p class="color_grey" id="product_name"></p>
                <p class="color_black bolder">le {$myArray[0]['date']}</p>
            </div>

        </div>
    </div>
</div>

{*<div class="modal">*}
    {*<div class="modal-wrapper" >*}
            {*<div class="row">*}
                {*<div class="col-xs-3">*}
                    {*<img src="{$img|escape:'html':'UTF-8'}"/>*}
                {*</div>*}
                {*<div class="col-xs-9">*}
                    {*<p class="color_black bolder">{$firstname} {$lastname} de {$country}, {$city} à acheté</p>*}
                    {*<p class="color_grey">{$name}</p>*}
                    {*<p class="color_black bolder">le {$date}</p>*}
                {*</div>*}

            {*</div>*}
    {*</div>*}
{*</div>*}

<input type="hidden" value="{$frequency}" id="frequency">
<input type="hidden" value="{$visibility_time}" id="visibility_time">
