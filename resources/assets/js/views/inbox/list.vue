<template>
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <js-plugin :js-plugin="data.js_plugin"></js-plugin>
        <!-- BreadCrumb	-->
        <breadcrumb :breadcrumb-data="data.breadcrumb"></breadcrumb>	        
        <div class="m-content">
            <div class="row">
                <!-- inbox part start -->
                <div class="col-lg-8">
                    <div class="m-portlet m-portlet--mobile">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text"> 
                                        Inbox
                                        
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            
                            <div class="m-messenger m-messenger--message-arrow m-messenger--skin-light">
                                <div class="m-messenger__messages mCustomScrollbar _mCS_22 mCS-autoHide">
                                    <div id="mCSB_22" class="mCustomScrollBox mCS-minimal-dark mCSB_vertical mCSB_outside" tabindex="0" style="max-height: none;">
                                        <div id="mCSB_22_container" class="mCSB_container" style="position:relative; top:0; left:0;" dir="ltr">
                                            <div class="m-messenger__wrapper" v-for="msg in data.data">
                                                <div class="m-messenger__message" :class="msg.direction == 'O' ? 'm-messenger__message--out' :'m-messenger__message--in'">                                                    
                                                    <div class="m-messenger__message-body">
                                                        <div class="m-messenger__message-arrow"></div>
                                                        <div class="m-messenger__message-content">
                                                            <div class="m-messenger__message-username">
                                                                {{msg.sms_from}}
                                                            </div>
                                                            <div class="m-messenger__message-text" style="word-break: break-all" v-html="msg.sms_text"></div>
                                                        </div>
                                                        <!-- <span class="badge badge-right">{{ data.status[msg.status] }}</span> -->
                                                    </div>
                                                </div>

                                                <div :class="msg.direction == 'O' ? 'm-messenger-datetime-right' : 'm-messenger-datetime-left'">{{msg.log_time | formatDate("ddd, MMM YY HH:mm A")}}</div>
                                            </div>
                                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="type_msg">
                                <div class="input_msg_write">                                    
                                <textarea type="text" class="write_msg" v-model="message" v-validate="'required|max:'+data.sms_text_size" placeholder="Type a message"></textarea>
                                <span class="m-form__help" v-if="message.length > data.sms_text_size" style="color: #c12222">
                                            The Message field may not be greater than {{data.sms_text_size}} characters.
                                </span><br />
                                <span class="limiter">{{calculateSMSParts}}</span>
                                <button class="msg_send_btn" @click="sendMessage()" type="button"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- inbox part end -->

                <!-- detail part start -->
                <div class="col-lg-4">
                    <div class="m-portlet m-portlet--mobile">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text"> 
                                        Details
                                        
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <div class="m-widget28">
                                <div class="m-widget28__container">
                                    <div class="m-widget28__tab tab-content">
                                        <div class="m-widget28__tab-container tab-pane active">
                                            <div class="m-widget28__tab-items">
                                                <div class="m-widget28__tab-item">
                                                    <span>DID</span> <span>{{ from | formatPhone}}</span>
                                                </div> 
                                                <div class="m-widget28__tab-item">
                                                    <span>Client Number</span> <span>{{ to | formatPhone}}</span>
                                                </div> 
                                                <div class="m-widget28__tab-item">
                                                    <span>Total Inbound</span> <span>{{ (data.inbound[0]) ? data.inbound[0].Total : 0 }}</span>
                                                </div> 
                                                <div class="m-widget28__tab-item">
                                                    <span>Total Outbound</span> <span>{{ (data.outbound[0]) ? data.outbound[0].Total : 0 }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- detail part end -->
            <!-- END EXAMPLE TABLE PORTLET-->		        
            </div>
        </div>
    </div>
</template>

<script>
import AppComponent from '../../components/AppComponent'

export default {
  extends: AppComponent,
    data() {
        return {
            base_url: 'api/inbox-list/'+this.from+'/'+this.to,
            persons: [],
            scheduleShow: false,
            compose: {to : []},
            message: '',
            from: '',
            to: '',
            date: null,         
            data: {},
            pagination: {},
        }
    },
    computed: {
        calculateSMSParts(){
        var
            smsType,
            smsLength = 0,
            smsCount = -1,
            charsLeft = 0,
            text = this.message,
            isUnicode = false;

            for(var charPos = 0; charPos < text.length; charPos++){
                switch(text[charPos]){
                    case "\n": 
                    case "[":
                    case "]":
                    case "\\":
                    case "^":
                    case "{":
                    case "}":
                    case "|":
                    case "€":
                        smsLength += 2;
                    break;

                    default:
                        smsLength += 1;
                }

                //!isUnicode && text.charCodeAt(charPos) > 127 && text[charPos] != "€" && (isUnicode = true)
                if(text.charCodeAt(charPos) > 127 && text[charPos] != "€")
                isUnicode = true;
            }

            if(isUnicode){
                smsType = this.data.sms_text_lengths_unicode;//lengths.unicode;
                this.data.sms_text_size = 201;
            }
            else{
                smsType = this.data.sms_text_lengths_ascii;//lengths.ascii;
                this.data.sms_text_size = 459;
            }

            for(var sCount = 0; sCount < this.data.sms_text_part; sCount++){

                //this.cutStrLength = smsType[sCount];
                if(smsLength <= smsType[sCount]){

                    smsCount = sCount + 1;
                    charsLeft = smsType[sCount] - smsLength;
                    break
                }
            }

            //if(this.cut) e.val(text.substring(0, this.cutStrLength));
            smsCount == -1 && (smsCount = this.data.sms_text_part, charsLeft = 0);

            if (typeof smsCount === 'undefined') {
                smsCount = 0;
            }

            return "Characters: " + smsLength + "/" + this.data.sms_text_size + " | " + "Parts: " + smsCount + "/" + this.data.sms_text_part;

    },
    charactersLeft() {
        var char = this.message.length,
            limit = this.data.sms_text_size;
        var remaining = limit - char;
          if(char > limit)
            char = limit;

        return "Characters: " + char + "/" + limit;
      },
      partsLeft() {
          var parts = this.message.length,limit = this.data.sms_text_part,part_size = this.data.sms_text_part_size;
          parts = Math.ceil(parts/part_size);
          var remaining = limit - parts;
          if(parts > limit)
            parts = limit;

        return "Parts: " + parts + "/" + limit;
      }
  },
    mounted() { 
        if(this.$route.params.from && this.$route.params.to){ 
            this.from = this.$route.params.from;
            this.to = this.$route.params.to;
            //console.log('From : '+this.from + 'To:'+ this.to);
            this.fetchInbound();
            this.bindCurrentRoute();
            this.scroll();
        }
    },
    methods: {
        // Fetch List
        scroll () {
            var vm = this;
            let page = 1;
            window.onscroll = () => {
                if($(window).scrollTop() == 0){
                    //console.log('here'); 
                    page = page + 1;                   
                    if(page <= this.pagination.last_page){
                        //console.log(this.pagination.last_page);
                        commonLib.blockUI({target: ".m-content", animate: true, overlayColor: 'none', top:'45%'});
                        axios.get('api/inbox-list/'+this.from+'/'+this.to+'?page='+page)
                        .then(res => {
                            //this.data = res.data;
                            //console.log(res.data.data);                            
                            let html = '';
                            let class1,class2,log_time,sms_from,sms_to,sms_text,direction;
                            let arr = $.map(res.data.data.reverse(), function(elm, index) {
                                class1 = (elm['direction']=='O') ? 'm-messenger__message--out' :'m-messenger__message--in';
                                class2 = (elm['direction']=='O') ? 'm-messenger-datetime-right' :'m-messenger-datetime-left';
                                log_time = vm.getDate(elm['log_time'],"ddd, MMM YY HH:mm A");sms_from = elm['direction'];sms_text = elm['sms_text'];
                                html += '<div class="m-messenger__wrapper"><div class="m-messenger__message '+class1+'"><div class="m-messenger__message-body"><div class="m-messenger__message-arrow"></div> <div class="m-messenger__message-content"><div class="m-messenger__message-username"></div> <div style="word-break: break-all" class="m-messenger__message-text">'+sms_text+'</div></div></div></div> <div class="'+class2+'">'+log_time+'</div></div>';
                            });                       
                            $("#mCSB_22_container").prepend(html);
                            commonLib.unblockUI(".m-content");
                        })
                        .catch( function(error) {
                            commonLib.iniToastrNotification("warning", "Warning", "Data could not be loaded.");
                            commonLib.unblockUI(".m-content");
                        }); 
                    }
                }
                
            };
        },
        getFormateDate(date){
            return this.$getFormateDate(date);
        },
        getDate(date, format){
            return moment(String(date)).format(format)
        },
        pushMessage(log_time){
            this.data.data.push({log_time: log_time, did:this.from,client:this.to,sms_text: this.message,status:'P',direction:'O'});
        },
        sendMessage() {
                    if(this.message.length > this.data.sms_text_size){
                        return;
                    }
                    var vm = this;
                    this.compose.to = this.to;
                    this.compose.from = this.from;
                    this.compose.message = this.message;
                    this.compose.scheduleShow = this.scheduleShow;
                    axios.post('api/reply-create', this.compose).then((res) => 
                    {
                        commonLib.iniToastrNotification(res.data.response_msg.type, res.data.response_msg.title, res.data.response_msg.message);
                        if(res.data.response_msg.type == 'success'){
                            var localTime = new Date().toLocaleString("en-US", {timeZone: this.data.timezone});                       
                            this.pushMessage(localTime);
                            //this.fetchInbound();
                            this.compose = {};
                            this.message = "";
                        }
                        commonLib.unblockUI(".m-content");
                    })
                    .catch(function (error) {
                        vm.validationErrors = error.response.data;
                        commonLib.unblockUI(".m-content");
                    });
        },
        
        fetchInbound(page_url) {            
            page_url = page_url || 'api/inbox-list/'+this.from+'/'+this.to+'?page='+this.pagination.current_page;
            this.getPagiDataReverse(page_url);
        }
    },
    beforeMount() {
        //this.getInitialUsers();
    }

}
</script>
