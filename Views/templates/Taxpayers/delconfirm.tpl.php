{topbar}
<div class="lowerbar w3-row w3-padding-24" style="width:100%">
                <div class="left w3-padding w3-white">
                    <h4>Taxpayers List</h4>
                    <div class="w3-container w3-border .cont" >
                        <form id="editForm" method="POST" action="Taxpayers/edit" style="display:none;">
                                
                            </form>
                        <div class="w3-padding w3-center" style="overflow-x:auto;">
                            
                            <h6>Do you really want to delete Taxpayer : <b>{tpin}</b>?</h6>
                            <a href="?tpin={tpin}&response=yes" class="w3-btn w3-card w3-orange w3-round-large">Yes</a><br/>
                            <br/>
                            <a href="../Taxpayers"  class="w3-btn w3-card w3-green w3-round-large">No</a><br/>
                        </div>
                        <div class="w3-row w3-padding more">
                            <a href="../Taxpayers" class="w3-button w3-dark-grey w3-text-white w3-round w3-left w3-card"><< Back To Taxpayers List</a>
                        </div>
                    </div>

                </div>
                
            </div>