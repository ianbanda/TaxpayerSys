<div class="w3-center loggedin" style="margin: auto;">
    {topbar}
    <div class="w3-center" style="margin: auto;">


        <div class="w3-border w3-white" style="max-width:250px;margin-top:100px;margin-left: auto;margin-right: auto">

            <form action="Auth/login" method="POST">
                <input type="hidden" name="loginform" />

                <div class="w3-padding">
                    <h6>
                        <input class="w3-input w3-text-green" placeholder="email" type="email" name="email" />
                    </h6>
                    <h6>
                        <input class="w3-input w3-text-green" placeholder="password" type="password" name="password" />
                    </h6>
                    <input class="w3-btn w3-green w3-round-large" value="Login" type="submit" style="width:100%" />
                </div>
                <span class="w3-text-orange">{login_error}</span>
            </form>

        </div>

    </div>
</div>