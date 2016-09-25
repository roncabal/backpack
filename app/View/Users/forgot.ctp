<?php echo $this->Html->css('bp-s-out-1'); ?>

<div id="headHolder" class="select-disable">
    <div id="titleHolder">
        <a href="<?php echo $this->Html->url('/login'); ?>" ><h1>Backpack</h1></a>
    </div>
</div>

<div id="contactnHolder">
	<div id="contactTitle">
		<h2>Can't remember what it was?</h2>
	</div>
</div>

<div id="csbody">
    <div id="forgotblock">
        <h1>Email</h1> 
        <div id="emailtb" class="input-control text">
            <input type="text" />
        </div>   
    </div>

    <div id="msgforgot">
        <h4>We'll send a reset password link to your registered email address.</h4> 
    </div>

    <div id="sendforgot">
        <button class="bg-color-darken fg-color-white">Send</button>
    </div>
</div>	

<div id="secondFooter" class="tile-group">
    <div id="backpackTileHolder" onclick="window.location='<?php echo $this->Html->url("/users/about"); ?>'">
        <div class="tile bg-color-greenDark">
            <div class="tile-content">
                <h2>Backpack</h2>

                <div class="bottomContent">
                    <h5>Read about Backpack</h5>
                </div>
            </div>
        </div>
    </div>

    <div id="contactHolder" onclick="window.location='<?php echo $this->Html->url("/users/contact"); ?>'">
        <div class="tile bg-color-pink">
            <div class="tile-content">
                <h3>Contact Us</h3>

                <div class="bottomContent">

                </div>
            </div>
        </div>
    </div>

    <div id="supportHolder" onclick="window.location='<?php echo $this->Html->url("/users/customer"); ?>'">
        <div class="tile double bg-color-purple">
            <div class="tile-content">
                <h2>Customer support</h2>

                <div class="bottomContent">
                    We care about you
                </div>
            </div>
        </div>
    </div>

    <div id="creditsHolder" onclick="window.location='<?php echo $this->Html->url("/users/credits"); ?>'">
        <div class="tile double bg-color-darken">
            <div class="tile-content">
                <h2>Credits</h2><br />
                <p>People who contributed to the creation of Backpack</p>
                <div class="bottomContent">
                    We thank them
                </div>
            </div>
        </div>
    </div>
</div>