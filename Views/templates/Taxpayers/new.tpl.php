<div class="w3-center loggedin" style="margin: auto;">
            {topbar}
            <div class="lowerbar w3-row">
                <div class="left w3-left w3-twothird w3-padding">
                    <div class="w3-container w3-white w3-border .cont w3-left-align">
                        
                        <form class="w3-container" id="newtpform" method="POST" action="">
                        <div class="w3-padding w3-left-align">
                            <h4>
                            <div class="w3-row">
                                <div class="w3-left">New Taxpayer Form</div>
                                <div class="w3-right w3-text-orange" style="font-size: 14px;">{formerror}</div>
                            </div>
                        </h4>
                                 <input type="hidden" name="taxpayerform" />
                                 
                                 <label class="w3-text-green" style="display:none"><b>Username</b></label>
                                <input  style="display:none" class="w3-input w3-border" name="username" type="text" value="{username}">
                                <span style="display:none">{username_error}</span>
                                <label class="w3-text-green"><b>TPIN</b></label>
                                <input class="w3-input w3-border" name="tpin" type="text" value="{tpin}">
                                {tpin_error}
                                <label class="w3-text-green"><b>Email</b></label>
                                <input class="w3-input w3-border" name="email" type="email" value="{email}">
                                {email_error}
                                
                                <label class="w3-text-green"><b>Physical Location</b></label>
                                <input class="w3-input w3-border" name="phyLocation" type="text" value="{phyLocation}">
                                {phyLocation_error}
                                <label class="w3-text-green"><b>Business Certificate Number</b></label>
                                <input class="w3-input w3-border" name="busCertNum" type="text" value="{busCertNum}">
                                {busCertNum_error}
                                <label class="w3-text-green"><b>Trading Name</b></label>
                                <input class="w3-input w3-border" name="tradingName" value="{tradingName}" type="text">
                                {tradingName_error}
                                <label class="w3-text-green"><b>Business Registration Date</b></label>
                                <input class="w3-input w3-border" name="busRegDate" type="text" value="{busRegDate}">
                                {busRegDate_error}
                                <label class="w3-text-green"><b>Mobile Number</b></label>
                                <input class="w3-input w3-border" name="mobileNumber" type="text" value="{mobileNumber}">
                                {mobileNumber_error}

                                
                            
                        </div>
                        <div class="w3-row w3-padding more">
                            <button class="w3-button w3-dark-grey w3-round w3-card w3-left" onclick="document.getElementById('newtpform').submit();">+ Save Taxpayer</button>
                            <a href="../Taxpayers" class="w3-button w3-green  w3-round w3-card w3-left w3-margin-left">- Cancel Creation</a>
                        </div>
                        </form>
                    </div>

                </div>
                <div class="right w3-quarter">
                    {rightbit}
                </div>
            </div>
        </div>