<template>
    <div class="m-grid__item m-grid__item--fluid m-wrapper">
        <js-plugin :js-plugin="data.js_plugin"></js-plugin>
        <!-- BreadCrumb	-->
        <breadcrumb :breadcrumb-data="data.breadcrumb"></breadcrumb>
        <div class="m-content">
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text"> 
                                SMS Dashboard
                            </h3>
                        </div>
                    </div>
                    
                </div>
                <div class="m-portlet__body">                    
                    <!--begin: Datatable -->
                    <div id="m_table_1_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="m-portlet__body">
		                            <div class="m-widget28">
                                        <div class="m-widget28__container" >	
                                            <div class="m-widget28__tab tab-content">
                                                <div class="m-widget28__tab-container tab-pane active"> 
                                                    <div v-if="data.accountInfo" class="m-widget28__tab-items">
                                                        <div class="m-widget28__tab-item">
                                                            <span>Account Name</span>
                                                            <span>{{data.accountInfo.name}}</span>
                                                        </div>
                                                        <div class="m-widget28__tab-item">
                                                            <span>Account ID</span>
                                                            <span>{{data.accountInfo.account_id}}</span>
                                                        </div>
                                                        <div class="m-widget28__tab-item">
                                                            <span>Account Balance</span>
                                                            <span>USD {{parseFloat(data.accountInfo.credit_amount - data.accountInfo.used_amount).toFixed(3)}}</span>
                                                            <span v-if="(data.accountInfo.credit_amount - data.accountInfo.used_amount) <= 0"><i>please talk to your management for top-up</i></span>
                                                        </div>
                                                        <div class="m-widget28__tab-item">
                                                            <span>Timezone</span>
                                                            <span>{{data.timezone}}</span>
                                                        </div>
                                                    </div>					      	 		      	
                                                </div>
                                            </div> 
                                        </div>
		                            </div>
                                </div>     
                            </div>
                            <div class="col-lg-6">
                                <div class="m-portlet m--bg-info m-portlet--bordered-semi m-portlet--full-height ">
                                    <div class="m-portlet__head">
                                        <div class="m-portlet__head-caption">
                                            <div class="m-portlet__head-title">
                                                <h3 class="m-portlet__head-text m--font-light"> 
                                                    Last 7 days
                                                </h3>
                                            </div>
                                        </div>
                                        
                                        <div class="m-portlet__head-tools">
                                            <a href="javascript:void(0);" class="btn btn-sm btn-secondary router-link-active" title="Refresh" @click.prevent="dashboardView"><span><i class="fa fa-refresh"></i></span></a>
                                        </div>
                                    </div>
                                    <div class="m-portlet__body">
                                        <!--begin::Widget 29-->
                                        <div class="m-widget29">			 
                                            <div v-if="data.inboundLog"  class="m-widget_content">
                                                <h3 class="m-widget_content-title">
                                                    <router-link href="javascript:void(0);"  v-bind:to="{name: 'InboundList'}" title="Inbound List">Inbound</router-link>
                                                </h3>
                                                <div class="m-widget_content-items">
                                                    <div class="m-widget_content-item">
                                                        <span>Status</span>
                                                        <span class="m--font-accent" style="font-size: 1.5rem;font-weight: 600;">Unread</span>
                                                        <span class="m--font-accent">Received</span>
                                                    </div>	
                                                    <div class="m-widget_content-item">
                                                        <span>Total</span>
                                                        <span style="font-size: 1.5rem;font-weight: 600;color: #fe21be;">{{data.unreadLog.total}}</span>
                                                        <span>{{data.inboundLog.total}}</span>
                                                    </div>
                                                </div>	
                                            </div>
                                            <div v-if="data.outboundLog" class="m-widget_content">
                                                <h3 class="m-widget_content-title">
                                                    <router-link href="javascript:void(0);"  v-bind:to="{name: 'OutboundList'}" title="Outbound List">Outbound</router-link>
                                                    </h3>
                                                <div class="m-widget_content-items">
                                                    <div class="m-widget_content-item">
                                                        <span>Status</span>
                                                        <span class="m--font-accent">Sent</span>
                                                    </div>	
                                                    <div class="m-widget_content-item">
                                                        <span>Total</span>
                                                        <span>{{data.outboundLog.total}}</span>
                                                    </div>
                                                </div>	
                                               
                                                <div class="m-widget_content-items">
                                                    <div class="m-widget_content-item">
                                                        <span>Status</span>
                                                        <span class="m--font-accent">Pending</span>
                                                    </div>	
                                                    <div class="m-widget_content-item">
                                                        <span>Total</span>
                                                        <span>{{data.smsInfo[0].pending}}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--end::Widget 29--> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->		        
        </div>
    </div>
</template>

<script>
import AppComponent from '../../components/AppComponent'
export default {
    extends: AppComponent,
    data() {
        return {
            data:{}
        };
    },
    mounted(){              
        this.dashboardView();         
        this.bindCurrentRoute("Dashboard");
    },
    methods: {
        dashboardView(){
            var url = 'api/dashboard';
            axios.get(url).then((res) => 
            { 
                this.data = res.data;
                this.$setDocumentTitle(this.data.title);
            })
            .catch(function (error) {
                console.log(error.response);
            });
        },
    }
};
</script>