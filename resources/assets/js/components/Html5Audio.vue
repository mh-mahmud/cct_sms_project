<template>
    <div class="row">
        <div class="col-md-12">
            <div id="equalizer" class="paused">
                <div v-for="n in 42" class="bar"></div>
            </div>
        </div>
        <div class="col-md-12">
            <audio id="callAudio" controls autoplay="true" v-on:pause="pauseEq()" v-on:play="playEq()" v-on:playing="playing()">
                <source :src="filePath" type="audio/mpeg">
            </audio>                            
        </div>
    </div>
   
</template>
<script>
  export default{
    data() {
        return {
        }
    },
    props:['filePath'],
    methods: {
        playEq(){
          $('#equalizer').removeClass('paused');
        },
        pauseEq(){
          $('#equalizer').addClass('paused');
        },
        playing(){
          $('#equalizer').removeClass('paused');
        }
    },
  }
</script>
<style>
audio{
    width: 450px;
}
#equalizer {
  margin-left: 31%;
  height: 40px;
}
#equalizer .bar {
  position: relative;
  float: left;
  background-color: #0081A5;
  height: 10px;
  margin-left: 1px;
  width: 3px;
  animation: jump 0ms -800ms linear infinite alternate;
}
@keyframes jump {
  0% {
    height: 10px;
    top: -5px;
  }
  100% {
    top: -15px;
    height: 30px;
  }
}
#equalizer .bar:nth-child(1) {
  animation-duration: 451ms;
}
#equalizer .bar:nth-child(2) {
  animation-duration: 494ms;
}
#equalizer .bar:nth-child(3) {
  animation-duration: 444ms;
}
#equalizer .bar:nth-child(4) {
  animation-duration: 437ms;
}
#equalizer .bar:nth-child(5) {
  animation-duration: 495ms;
}
#equalizer .bar:nth-child(6) {
  animation-duration: 453ms;
}
#equalizer .bar:nth-child(7) {
  animation-duration: 454ms;
}
#equalizer .bar:nth-child(8) {
  animation-duration: 486ms;
}
#equalizer .bar:nth-child(9) {
  animation-duration: 442ms;
}
#equalizer .bar:nth-child(10) {
  animation-duration: 470ms;
}
#equalizer .bar:nth-child(11) {
  animation-duration: 490ms;
}
#equalizer .bar:nth-child(12) {
  animation-duration: 446ms;
}
#equalizer .bar:nth-child(13) {
  animation-duration: 485ms;
}
#equalizer .bar:nth-child(14) {
  animation-duration: 440ms;
}
#equalizer .bar:nth-child(15) {
  animation-duration: 487ms;
}
#equalizer .bar:nth-child(16) {
  animation-duration: 465ms;
}
#equalizer .bar:nth-child(17) {
  animation-duration: 447ms;
}
#equalizer .bar:nth-child(18) {
  animation-duration: 459ms;
}
#equalizer .bar:nth-child(19) {
  animation-duration: 483ms;
}
#equalizer .bar:nth-child(20) {
  animation-duration: 437ms;
}


#equalizer .bar:nth-child(21) {
  animation-duration: 487ms;
}
#equalizer .bar:nth-child(22) {
  animation-duration: 494ms;
}
#equalizer .bar:nth-child(33) {
  animation-duration: 444ms;
}
#equalizer .bar:nth-child(24) {
  animation-duration: 437ms;
}
#equalizer .bar:nth-child(25) {
  animation-duration: 495ms;
}
#equalizer .bar:nth-child(26) {
  animation-duration: 453ms;
}
#equalizer .bar:nth-child(27) {
  animation-duration: 454ms;
}
#equalizer .bar:nth-child(28) {
  animation-duration: 486ms;
}
#equalizer .bar:nth-child(29) {
  animation-duration: 442ms;
}
#equalizer .bar:nth-child(30) {
  animation-duration: 470ms;
}
#equalizer .bar:nth-child(11) {
  animation-duration: 490ms;
}
#equalizer .bar:nth-child(32) {
  animation-duration: 446ms;
}
#equalizer .bar:nth-child(33) {
  animation-duration: 485ms;
}
#equalizer .bar:nth-child(34) {
  animation-duration: 440ms;
}
#equalizer .bar:nth-child(35) {
  animation-duration: 487ms;
}
#equalizer .bar:nth-child(36) {
  animation-duration: 465ms;
}
#equalizer .bar:nth-child(37) {
  animation-duration: 447ms;
}
#equalizer .bar:nth-child(38) {
  animation-duration: 459ms;
}
#equalizer .bar:nth-child(39) {
  animation-duration: 483ms;
}
#equalizer .bar:nth-child(40) {
  animation-duration: 457ms;
}
#equalizer .bar:nth-child(41) {
  animation-duration: 477ms;
}
#equalizer .bar:nth-child(42) {
  animation-duration: 487ms;
}

#equalizer.paused .bar {
  animation-play-state: paused;
}
</style>
