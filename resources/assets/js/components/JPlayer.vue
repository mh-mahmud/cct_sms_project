<!-- http://jplayer.org -->
<template>
    <div class="container">
        <div id="jquery_jplayer" class="jp-jplayer"></div>
        <div id="jp_container" class="jp-audio" role="application" aria-label="media player">
            <div class="jp-type-playlist">
                <div class="jp-gui jp-interface">
                    <div class="jp-controls">
                        <button class="jp-previous" role="button" tabindex="0">previous</button>
                        <button class="jp-play" role="button" tabindex="0">play</button>
                        <button class="jp-next" role="button" tabindex="0">next</button>
                        <button class="jp-stop" role="button" tabindex="0">stop</button>
                    </div>
                    <div class="jp-progress">
                        <div class="jp-seek-bar">
                            <div class="jp-play-bar"></div>
                        </div>
                    </div>
                    <div class="jp-volume-controls">
                        <button class="jp-mute" role="button" tabindex="0">mute</button>
                        <button class="jp-volume-max" role="button" tabindex="0">max volume</button>
                        <div class="jp-volume-bar">
                            <div class="jp-volume-bar-value"></div>
                        </div>
                    </div>
                    <div class="jp-time-holder">
                        <div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
                        <div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
                    </div>
                    <div class="jp-toggles">
                        <button class="jp-repeat" role="button" tabindex="0">repeat</button>
                        <button class="jp-shuffle" role="button" tabindex="0">shuffle</button>
                        <button class="jp-full-screen" role="button" tabindex="0">full screen</button>
                    </div>
                </div>
                <div class="jp-playlist">
                    <ul>
                        <li>&nbsp;</li>
                    </ul>   
                </div>
            </div>
        </div>
    </div>
</template>
<style>
/*@import '../../../../public/assets/plugins/jPlayer/dist/skin/blue.monday/css/jplayer.blue.monday.min.css';*/
</style>
<script>
// require('../../../../public/assets/plugins/jPlayer/dist/jplayer/jquery.jplayer.min.js')
// require('../../../../public/assets/plugins/jPlayer/dist/add-on/jplayer.playlist.min.js')
export default{
    data() {
        return {
        }
    },
    props:['playList','options'],
    mounted() { 
        this.initJplayer(this.playList, this.options);
    },
    methods: {
        initJplayer(playList, options){
            console.log(options);
            var config = {
                swfPath: "../../../../public/assets/plugins/jPlayer/dist/jplayer",
                supplied: "mp3",
                wmode: "window",
                useStateClassSkin: true,
                autoBlur: false,
                smoothPlayBar: true,
                keyEnabled: true,
                remainingDuration: true,
                toggleDuration: true,
                fullScreen: true
            };

            $.extend( true, config, options );
                new jPlayerPlaylist({
                    jPlayer: "#jquery_jplayer",
                    cssSelectorAncestor: "#jp_container"
                }, 
                playList, config
            );
            
        }
    },
}
</script>
