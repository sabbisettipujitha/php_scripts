<script type="text/javascript" src="raphael-min.js"></script>
<script>
//------------------------------------------------------
// User Interface code to control the clocks in the page
//------------------------------------------------------

function create_clocks() {
    var now = new Date();
    var Tokyo = new Date();
    var Newyork = new Date();
	var London = new Date();
	
    var hours = now.getUTCHours();
    Tokyo.setHours(hours + +9);
    Newyork.setHours(hours + -4);
	London.setHours(hours + +1);
	
    new clock("clock_id_01", Tokyo).start();
    new clock("clock_id_02", Newyork).start();
    new clock("clock_id_03",London).start();
}

//----------------------------------------------------
// clock class code
//----------------------------------------------------

function clock(id, initialTime) 
{
    // we store each clock in global map clock.clocks
    // create global clock map if it doesn't already exist
    clock.clocks = clock.clocks || {};
    // store this newly created clock in the map
    clock.clocks[id] = this;
    this.id = id;
    // canvas for this clock (remembered as an instance variable)
    this.canvas = Raphael(id, 100, 100);
    
    // draw clock face
    var clockFace = this.canvas.circle(50,50,45);
    clockFace.attr({"fill":"","stroke":"","stroke-width":"5"})  
        
    // draw clock tick marks
    var start_x, start_y, end_x, end_y;
    for(i=0;i<12;i++){
        start_x = 50+Math.round(30*Math.cos(30*i*Math.PI/180));
        start_y = 50+Math.round(30*Math.sin(30*i*Math.PI/180));
        end_x = 50+Math.round(40*Math.cos(30*i*Math.PI/180));
        end_y = 50+Math.round(40*Math.sin(30*i*Math.PI/180));  
        this.canvas.path("M"+start_x+" "+start_y+"L"+end_x+" "+end_y);
    }
    
    // draw the three hands (hour, minutes, seconds)
    // save each path as an instance variable
    this.hour_hand = this.canvas.path("M50 50L50 25");
    this.hour_hand.attr({stroke: "#444444", "stroke-width": 6});
    this.minute_hand = this.canvas.path("M50 50L50 15");
    this.minute_hand.attr({stroke: "#444444", "stroke-width": 4});
    this.second_hand = this.canvas.path("M50 60L50 10");
    this.second_hand.attr({stroke: "#444444", "stroke-width": 2}); 
    
    // draw center pin
    var pin = this.canvas.circle(50, 50, 5);
    pin.attr("fill", "#000000");    

    // update with the actual time
    this.drawTime(initialTime);
 }

clock.prototype = {  
    // start the clock running automatically
    start: function() {
        // we have just one global timer running
        // check to see if it is going - if not start it
        if (!clock.timer) {
            clock.timer = setInterval(function() {
                var clocks = clock.clocks;   // get global map
                for (var i in clocks) {
                    if (clocks.hasOwnProperty(i)) {
                        if (clocks[i].running) {
                            clocks[i].update();
                        }
                    }
                }
            }, 1000);
        }
        // if we weren't already running, start this clock
        if (!this.running) {
            var now = new Date();
            this.timeOffset = now - this.currentTime;
            this.update();
            this.running = true;
        }
        
        return(this);
    },
    
    // stop the clock
    stop: function() {
        this.running = false;
    },
    
    destroy: function() {
        this.stop();
        delete clock.clocks[this.id];
    },
    
    // update the clock according to time of day
    update: function() {
        var now = new Date();
        this.drawTime(new Date(now - this.timeOffset));
    },   
    
    // update the clock - if no time is passed in, then it will use the current time
    drawTime: function(customDate) {
        var now = customDate || new Date();
        var hours = now.getHours();
        var minutes = now.getUTCMinutes();
        var seconds = now.getUTCSeconds();
        this.hour_hand.rotate(30*hours+(minutes/2.5), 50, 50);
        this.minute_hand.rotate(6*minutes, 50, 50);
        this.second_hand.rotate(6*seconds, 50, 50);
        this.currentTime = now;
    }
};

</script>
<style>
.clock {float: left; margin: 20px;}
.buttons {text-align: center; margin-bottom: 10px;}
.label {text-align: center;}
</style>
<html>    
    <body> 
        <div>
            <div>Welcome To JAPS INFOTECH</div>
            <div class="clock">
               <div class="label">
                 Tokyo
                </div>
                <div id="clock_id_01"></div>
            </div>
        </div>
        <div class="clock">
            <div class="label">
               New York 
            </div>
            <div id="clock_id_02"></div>
        </div>
        <div class="clock">
             <div class="label">
                London
            </div>
            <div id="clock_id_03"></div>
        </div>
    <script>create_clocks()</script>
    </body> 
</html>
