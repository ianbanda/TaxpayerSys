<div class="w3-center loggedin" style="margin: auto;">
            {topbar}
            <div class="lowerbar w3-row">
                <div class="left w3-left w3-twothird w3-padding">
                    <div class="w3-container w3-white w3-border .cont w3-left-align">
                        
                        <form class="w3-container" id="newtpform" method="POST" action="">
                        <div class="w3-padding w3-left-align">
                            <h4>
                            <div class="w3-row">
                                <div class="w3-left">New Taxpayer Created</div>
                                <div class="w3-right w3-text-orange" style="font-size: 14px;">{formerror}</div>
                            </div>
                        </h4>
                                 <input type="hidden" name="taxpayerform" />
                                 
                                
                                <label class="w3-text-green"><b>TPIN</b></label>
                                {tpin}<br/>
                                <label class="w3-text-green"><b>Email</b></label>
                                {email}<br/>
                                <label class="w3-text-green"><b>Physical Location</b></label>
                                {phyLocation}<br/>
                                <label class="w3-text-green"><b>Business Certificate Number</b></label>
                                {busCertNum}<br/>
                                <label class="w3-text-green"><b>Trading Name</b></label>
                                {tradingName}<br/>
                                <label class="w3-text-green"><b>Business Registration Date</b></label>
                                {busRegDate}<br/>
                                <label class="w3-text-green"><b>Mobile Number</b></label>
                                {mobileNumber}
                            
                        </div>
                        <div class="w3-row w3-padding more">
                            <a href="../Taxpayers" class="w3-button w3-dark-grey  w3-round w3-card w3-left w3-margin-left"><< View Taxpayer List</a>
                            <a href="../Taxpayers/add" class="w3-button w3-green  w3-round w3-card w3-left w3-margin-left">- Create Another Taxpayer</a>
                        </div>
                        </form>
                    </div>

                </div>
                <div class="right w3-quarter">
                    {rightbit}
                </div>
            </div>
        </div>