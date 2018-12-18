<template>
    <div class="dashboard-editor-container" :style="{height: this.$store.getters.tableHeight + 218 + `px`}">
        <el-row>
            <el-col :xs="24" :sm="24" :lg="11">
                <div class="project-left_name">
                    <h4 class="name">{{ userData.company.company.ywlxr }}，您好！</h4>
                    <p class="describe">{{userData.company.company.gsmc}}</p>
                </div>
            </el-col>
            <el-col :xs="24" :sm="24" :lg="13">
                <div class="project-right_type">
                    <div class="card-panel-icon-wrapper">
                        <router-link class="link-type" :to="{ name: 'orderList'}">
                            <svg-icon icon-class="dingdan" class-name="card-panel-icon"/>
                            <p>订单明细</p>
                        </router-link>
                    </div>
                    <div class="card-panel-icon-wrapper">
                        <router-link class="link-type" :to="{ name: 'customerList'}">
                            <svg-icon icon-class="kehu" class-name="card-panel-icon"/>
                            <p>客户管理</p>
                        </router-link>
                    </div>
                    <div class="card-panel-icon-wrapper">
                        <router-link class="link-type" :to="{ name: 'productInput'}">
                            <svg-icon icon-class="shangpin" class-name="card-panel-icon"/>
                            <p>商品管理</p>
                        </router-link>
                    </div>
                    <!--<div class="card-panel-icon-wrapper">-->
                    <!--<router-link class="link-type" :to="{ name: 'product.promotion.management'}">-->
                    <!--<svg-icon icon-class="cuxiao" class-name="card-panel-icon"/>-->
                    <!--<p>促销管理</p>-->
                    <!--</router-link>-->
                    <!--</div>-->
                    <div class="card-panel-icon-wrapper">
                        <router-link class="link-type" :to="{ name: 'personDriver'}">
                            <svg-icon icon-class="yewuyuan" class-name="card-panel-icon"/>
                            <p>司机管理</p>
                        </router-link>
                    </div>
                    <div class="card-panel-icon-wrapper">
                        <router-link class="link-type" :to="{ name: 'personal.finance'}">
                            <svg-icon icon-class="jinrong" class-name="card-panel-icon"/>
                            <p>金融贷款</p>
                        </router-link>
                    </div>
                    <div class="card-panel-icon-wrapper">
                        <router-link class="link-type" :to="{ name: 'reportProduct'}">
                            <svg-icon icon-class="baobiao" class-name="card-panel-icon"/>
                            <p>报表</p>
                        </router-link>
                    </div>
                </div>
            </el-col>
        </el-row>
        <panel-group :panel-data="homeData"></panel-group>
        <el-row :gutter="10">
            <el-col :xs="24" :sm="24" :lg="12">
                <div class="chart-wrapper-left achievement">
                    <h5 class="title">业绩环比 <span title="包含所有订单状态的业绩数据（无效订单和无法供货订单除外）"><svg-icon icon-class="help" class-name="card-panel-icon"/></span></h5>
                    <el-row :gutter="10">
                        <el-col :xs="24" :sm="12" :lg="12">
                            <div class='card-panel'>
                                <div class="card-panel-icon-wrapper icon-people"
                                     :title="homeData.today_order_info.count+'单'">
                                    今日订单<em>{{homeData.today_order_info.count}}</em>单
                                </div>
                                <div class="card-panel-description">
                                    <div class="card-panel-text">{{homeData.today_order_info.commission || 0}}元</div>
                                    <div class="card-panel-num">
                                        <span>日环比</span>
                                        <span v-if="homeData.today_order_info.ratio != 0"
                                              :title="homeData.today_order_info.ratio+'%'">
                                            <svg-icon v-if="homeData.today_order_info.is_grow" icon-class="up"
                                                      class-name="card-panel-icon"/>
                                            <svg-icon v-else icon-class="down" class-name="card-panel-icon icon-down"/>
                                            {{homeData.today_order_info.ratio}}%
                                        </span>
                                        <span v-else>--</span>
                                    </div>
                                </div>
                            </div>
                        </el-col>
                        <el-col :xs="24" :sm="12" :lg="12">
                            <div class='card-panel'>
                                <div class="card-panel-icon-wrapper icon-people"
                                     :title="homeData.last_order_info.count+'单'">
                                    昨日订单<em>{{homeData.last_order_info.count}}</em>单
                                </div>
                                <div class="card-panel-description">
                                    <div class="card-panel-text">{{homeData.last_order_info.commission || 0}}元</div>
                                    <div class="card-panel-num">
                                        <span>日环比</span>
                                        <span v-if="homeData.last_order_info.ratio != 0"
                                              :title="homeData.last_order_info.ratio+'%'">
                                            <svg-icon v-if="homeData.last_order_info.is_grow" icon-class="up"
                                                      class-name="card-panel-icon"/>
                                            <svg-icon v-else icon-class="down" class-name="card-panel-icon icon-down"/>
                                            {{homeData.last_order_info.ratio}}%
                                        </span>
                                        <span v-else>--</span>
                                    </div>
                                </div>
                            </div>
                        </el-col>
                        <el-col :xs="24" :sm="12" :lg="12">
                            <div class='card-panel'>
                                <div class="card-panel-icon-wrapper icon-people"
                                     :title="homeData.month_order_info.count+'单'">
                                    本月订单<em>{{homeData.month_order_info.count}}</em>单
                                </div>
                                <div class="card-panel-description">
                                    <div class="card-panel-text">{{homeData.month_order_info.commission || 0}}元</div>
                                    <div class="card-panel-num">
                                        <span>月环比</span>
                                        <span v-if="homeData.month_order_info.ratio != 0"
                                              :title="homeData.month_order_info.ratio+'%'">
                                            <svg-icon v-if="homeData.month_order_info.is_grow" icon-class="up"
                                                      class-name="card-panel-icon"/>
                                            <svg-icon v-else icon-class="down" class-name="card-panel-icon icon-down"/>
                                            {{homeData.month_order_info.ratio}}%
                                        </span>
                                        <span v-else>--</span>
                                    </div>
                                </div>
                            </div>
                        </el-col>
                        <el-col :xs="24" :sm="12" :lg="12">
                            <div class='card-panel'>
                                <div class="card-panel-icon-wrapper icon-people"
                                     :title="homeData.last_month_order_info.count+'单'">
                                    上月订单<em>{{homeData.last_month_order_info.count}}</em>单
                                </div>
                                <div class="card-panel-description">
                                    <div class="card-panel-text">{{homeData.last_month_order_info.commission || 0}}元
                                    </div>
                                    <div class="card-panel-num">
                                        <span>月环比</span>
                                        <span v-if="homeData.last_month_order_info.ratio != 0"
                                              :title="homeData.last_month_order_info.ratio+'%'">
                                            <svg-icon v-if="homeData.last_month_order_info.is_grow" icon-class="up"
                                                      class-name="card-panel-icon"/>
                                            <svg-icon v-else icon-class="down" class-name="card-panel-icon icon-down"/>
                                            {{homeData.last_month_order_info.ratio}}%
                                        </span>
                                        <span v-else>--</span>
                                    </div>
                                </div>
                            </div>
                        </el-col>
                    </el-row>
                </div>
            </el-col>
            <el-col :xs="24" :sm="24" :lg="12">
                <div class="chart-wrapper-right achievement">
                    <h5 class="title">月业绩环比 <span title="包含所有订单状态的业绩数据（无效订单和无法供货订单除外）"><svg-icon icon-class="help" class-name="card-panel-icon"/></span></h5>
                    <line-chart :chart-data="lineChartData"></line-chart>
                </div>
            </el-col>
        </el-row>
        <el-row :gutter="32">
            <el-col :xs="24" :sm="24" :lg="24">
                <div class="map-wrapper">
                    <div class="search-map">
                        <span class="title-name">地图分布</span>
                        <el-form :inline="true" class="demo-form-inline" @submit.native.prevent>
                            <!--<el-form-item label="业务员">-->
                            <!--<el-input placeholder="请输入业务员姓名" @keyup.enter="getMapList('refresh')"-->
                            <!--@change="getMapList()" clearable></el-input>-->
                            <!--</el-form-item>-->
                            <!--<el-form-item label="司机">-->
                            <!--<el-input placeholder="请输入司机姓名" @keyup.enter="getMapList('refresh')"-->
                            <!--@change="getMapList()" clearable></el-input>-->
                            <!--</el-form-item>-->
                            <el-form-item label="客户">
                                <el-input placeholder="请输入客户名称" v-model="search.customer_name"
                                          @keyup.enter="getMapList()"
                                          @change="getMapList()" clearable></el-input>
                            </el-form-item>
                        </el-form>
                    </div>
                    <div class="map-wrapper-con">
                        <baidu-map class="bm-view" :center="center" :zoom="11" @ready="mapHandler">
                            <bm-navigation anchor="BMAP_ANCHOR_TOP_RIGHT"></bm-navigation>
                            <bm-control>
                                <div class="remark-icon">
                                    <svg-icon icon-class="kehumap" class-name="card-panel-icon"/>
                                    客户
                                </div>
                                <!--<div class="remark-icon">-->
                                <!--<svg-icon icon-class="jjrmap" class-name="card-panel-icon"/>业务员-->
                                <!--</div>-->
                                <!--<div class="remark-icon">-->
                                <!--<svg-icon icon-class="siji" class-name="card-panel-icon"/>司机-->
                                <!--</div>-->
                            </bm-control>
                            <bm-info-window :position="infoWindow.position" :title="infoWindow.title"
                                            :show="infoWindow.show" :offset="{width: 0, height: -17}"
                                            @close="infoWindowClose" @open="infoWindowOpen">
                                <p v-text="infoWindow.contents"></p>
                            </bm-info-window>
                            <!--<template v-for="mark in mapList">-->
                                <!--客户-->
                                <bm-marker v-for="(mark,index) in mapList" :key="index" v-if="mark.type === '客户'" :position="{lng:mark.longitude,lat:mark.latitude}"
                                           :dragging="false"
                                           @mouseover="showMarker(mark)" @click="setMapCenter(mark)" @mouseout="infoWindowClose" :zIndex="999"
                                           :icon="{url: 'data:image/svg+xml;base64,77u/PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIyOHB4IiBoZWlnaHQ9IjM0cHgiIHZpZXdCb3g9IjAgMCAyOCAzNCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMjggMzQiIHhtbDpzcGFjZT0icHJlc2VydmUiPiAgPGltYWdlIGlkPSJpbWFnZTAiIHdpZHRoPSIyOCIgaGVpZ2h0PSIzNCIgeD0iMCIgeT0iMCIKICAgIHhsaW5rOmhyZWY9ImRhdGE6aW1hZ2UvcG5nO2Jhc2U2NCxpVkJPUncwS0dnb0FBQUFOU1VoRVVnQUFBQndBQUFBaUNBTUFBQUI3bzBsN0FBQUFCR2RCVFVFQUFMR1BDL3hoQlFBQUFDQmpTRkpOCkFBQjZKZ0FBZ0lRQUFQb0FBQUNBNkFBQWRUQUFBT3BnQUFBNm1BQUFGM0NjdWxFOEFBQUJNbEJNVkVYL2pFNy8vLy8vakU3L2pFNy8KakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3LwpqRTcvakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvCmpFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy8KakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy9qRTcvakU3L2pFNy9ySC8vNE5ELytmYi93Wi8vLy83Ly8vLy93Si8vcG5iLwoyTVAvMmNYLzcrYi83dVgvMmNULzJzWC9wM2ovalUvL21XSC94Nm4vOCt6L29tLy8rdmYvOXZMLy9mei96N1gvcFhUL2oxUC85L1AvCnlLdi9qVkQveGFVdjk4N2pBQUFBUjNSU1RsTUFBQUpCak1YbytRUm4zZDRzMFZIM1V2eFRNUVhXQm05dzVrbEtsTW5JN1B2ejlOZWkKcFZkYUNldnZESUdKRHhSZ2FBRzF2aGZsNmh3NlcvNTRqcEtjbUorYWo0aUxoT2NtZmNJQUFBQUJZa3RIUkFIL0FpM2VBQUFBQjNSSgpUVVVINGdzYUVDZ2QvVkgzd0FBQUFWeEpSRUZVS005MWszbERna0FReFFjdnNEUXlzN0lzdSsrVGJyVzdYRERKc2tORjgvYjdmNFVRClp1VFEzbDl2M285ZFlHY1dBT1h4K3Z3Qm5nLzRmVjRQaDBJa0JNZllRT05Cd1E1RFllWlFPR1RCQ1pHNUpFNFFuSXl3SVVXbVRCZ1YKMlFpSjBUNmNqbEV0SzdtY0lsTVZtOUhoTEZXdmVWVlgvbzNxT1E2RU9QcUN3WFJhd0NBdXdEdzkrSzZpUGloWmdBVFpJc0ZQU2hLdwpPQVNMbEN4Qmt1d1h3VzlLa3JCTTlzZjlRU3dDSytoS1pmcVZjZ21qVmFBanFHaFZXYW5WRkxtcVZUQmFnM1YwdjJxOTBXeTFtbzI2CjJzWm9BemJScVRaaHRBWGJPLy9CM1QyQWZkTzJMWWJiSHVnSGYyZ3U3Vml3WXk0ODZ2ZnoyUERkbm1ZaXJkYzFnaE9qMmRMcHFHYWYKZWN3eE9iOFlacGRYTkdEWHZKdnhYbXMwVTJrblM2ZnNRNTF4d294ajR1SEd6aExPNndEU3JjWHVKQmNFNlo3WWcvc2k5U2xPMCtPQQoyU0JJVDhiN0pHNFVCSGhtN01YR25CQ3lXYkF4N2cvQ1JicDZGNG5Fc0FBQUFDVjBSVmgwWkdGMFpUcGpjbVZoZEdVQU1qQXhPQzB4Ck1TMHlObFF4TmpvME1Eb3lPU3N3T0Rvd01Ha29ISm9BQUFBbGRFVllkR1JoZEdVNmJXOWthV1o1QURJd01UZ3RNVEV0TWpaVU1UWTYKTkRBNk1qa3JNRGc2TURBWWRhUW1BQUFBR1hSRldIUlRiMlowZDJGeVpRQkJaRzlpWlNCSmJXRm5aVkpsWVdSNWNjbGxQQUFBQUFCSgpSVTVFcmtKZ2dnPT0iIC8+Cjwvc3ZnPgo=', size: {width: 30, height: 35}}"></bm-marker>
                                <!--业务员-->
                                <!--<bm-marker v-if="mark.type === '业务员'" :position="{lng:113.666198,lat:34.753084}" :dragging="false"-->
                                <!--animation="BMAP_ANIMATION_BOUNCE"-->
                                <!--:icon="{url: 'data:image/svg+xml;base64,77u/PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIyOHB4IiBoZWlnaHQ9IjM0cHgiIHZpZXdCb3g9IjAgMCAyOCAzNCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMjggMzQiIHhtbDpzcGFjZT0icHJlc2VydmUiPiAgPGltYWdlIGlkPSJpbWFnZTAiIHdpZHRoPSIyOCIgaGVpZ2h0PSIzNCIgeD0iMCIgeT0iMCIKICAgIHhsaW5rOmhyZWY9ImRhdGE6aW1hZ2UvcG5nO2Jhc2U2NCxpVkJPUncwS0dnb0FBQUFOU1VoRVVnQUFBQndBQUFBaUNBTUFBQUI3bzBsN0FBQUFCR2RCVFVFQUFMR1BDL3hoQlFBQUFDQmpTRkpOCkFBQjZKZ0FBZ0lRQUFQb0FBQUNBNkFBQWRUQUFBT3BnQUFBNm1BQUFGM0NjdWxFOEFBQUJibEJNVkVYL1RrNy8vLy8vVGs3L1RrNy8KVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3LwpUazcvVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvClRrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy8KVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy9UazcvVGs3L1RrNy9oNGYvLy8vLzl2Yi9uSnovV0ZqLzFOVC81K2YvV2xyLwozTnovOGZIL1VsTC83ZTMvL3Y3L2JtNy9oSVQvajQvL3Y3Ly9mMy8vVDAvL3djSC94OGYvdmIzL3A2Zi85L2Yvb2FIL1lXSC9lbnIvCmlJai8xdGIvN3U3L3M3UC9yNi8vbnA3LzZ1ci84dkwvZ29ML2JXMy9ob2IvcGFYL1hWMy80dUwvK2ZuL1pXWC9WRlQvWGw3L2Ftci8KdUxqLzdPei95TWovZUhncTdUZTZBQUFBUjNSU1RsTUFBQUpCak1YbytRUm4zZDRzMFZIM1V2eFRNUVhXQm05dzVrbEtsTW5JN1B2ego5TmVpcFZkYUNldnZESUdKRHhSZ2FBRzF2aGZsNmh3NlcvNTRqcEtjbUorYWo0aUxoT2NtZmNJQUFBQUJZa3RIUkFIL0FpM2VBQUFBCkIzUkpUVVVINGdzYUVDY0c4S3dpNHdBQUFXOUpSRUZVS005dDAxZGJ3akFVQnVCVENyUW9VQkZSVVJUM25uVURicVZHY2FBNFFWRWMKdVBmODkzWWthVnI0cm5yeU5zblQ1Z1FBeDhFN1hXNUJjTHVjdklQRHdTUjZLaFNhU28vSW90ZW5XT0x6bXVpWEZGc2tQOEdxZ0ZLUwpRTFdCUVVrcEV5bW9ZVTNJcU5iUXVoYTBnVFZVcTJJZExsS2JTTXZXTnBsYno0RVlwaXZ0cUpaTzBUSXNRb081emU0ZXl1d3oyelpDCmhLblNLSFBBbEJGb01vdERkZG1qNHhOYU4wUFV4S3lLdWRNeldrZWhoVjBWb2Z4NS9vTCtDR2lsVnRBLzVmTHFtZ3kwUVlqaWpZN0YKVzBRRzJxR0Q0cDJPOXc4Rk10QUpYZVR4RVJsNW9tOTNRMDh2Zm56R21DUFcxdzh3WUZsVnpRc2VHRlIvL0JDZStvcnQ3UjFQSE5iTwpjOFFvUG9xNlpUL3h4Rkg5c09VeFhINTkvL3ora1IzSEhVYWJURXlXTnNMVU5HbXdHY0Z1QW0rMlppeHV0WGlNYmVxRUZST1dqb2RaCjFpTFc2d0R5bkduenNnMUJYaUMyYUw5SW11SnVXcUxHSU1qTCtuNHlWdzRCVmhSbGxURXJRaklKakhIL2UxVzZzcDZMOEU4QUFBQWwKZEVWWWRHUmhkR1U2WTNKbFlYUmxBREl3TVRndE1URXRNalpVTVRZNk16azZNRFlyTURnNk1EQUlhMVZIQUFBQUpYUkZXSFJrWVhSbApPbTF2WkdsbWVRQXlNREU0TFRFeExUSTJWREUyT2pNNU9qQTJLekE0T2pBd2VUYnQrd0FBQUJsMFJWaDBVMjltZEhkaGNtVUFRV1J2ClltVWdTVzFoWjJWU1pXRmtlWEhKWlR3QUFBQUFTVVZPUks1Q1lJST0iIC8+Cjwvc3ZnPgo=', size: {width: 300, height: 157}}"></bm-marker>-->
                                <!--司机-->
                                <!--<bm-marker v-if="mark.type === '司机'" :position="{lng:113.631703,lat:34.74644}" :dragging="false"-->
                                <!--animation="BMAP_ANIMATION_BOUNCE"-->
                                <!--:icon="{url: 'data:image/svg+xml;base64,77u/PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhRE9DVFlQRSBzdmcgUFVCTElDICItLy9XM0MvL0RURCBTVkcgMS4xLy9FTiIgImh0dHA6Ly93d3cudzMub3JnL0dyYXBoaWNzL1NWRy8xLjEvRFREL3N2ZzExLmR0ZCI+CjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSIyOHB4IiBoZWlnaHQ9IjM0cHgiIHZpZXdCb3g9IjAgMCAyOCAzNCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgMjggMzQiIHhtbDpzcGFjZT0icHJlc2VydmUiPiAgPGltYWdlIGlkPSJpbWFnZTAiIHdpZHRoPSIyOCIgaGVpZ2h0PSIzNCIgeD0iMCIgeT0iMCIKICAgIHhsaW5rOmhyZWY9ImRhdGE6aW1hZ2UvcG5nO2Jhc2U2NCxpVkJPUncwS0dnb0FBQUFOU1VoRVVnQUFBQndBQUFBaUNBTUFBQUI3bzBsN0FBQUFCR2RCVFVFQUFMR1BDL3hoQlFBQUFDQmpTRkpOCkFBQjZKZ0FBZ0lRQUFQb0FBQUNBNkFBQWRUQUFBT3BnQUFBNm1BQUFGM0NjdWxFOEFBQUJmVkJNVkVWZ3cyUC8vLzlndzJOZ3cyTmcKdzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZwp3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5nCncyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmcKdzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyTmd3Mk5ndzJOZ3cyT3g0clB5K3ZMMSsvWDArL1R6K3ZPMTQ3Zi8vLzlzeDI5Nwp6WDJBejRQMy9QZU8xSkR6Ky9PRzBZajkvdjNENk1URTZjWDYvZnB5eW5WdXlIRHgrdkdVMTVhVjE1ZVExWkxXOE5lOTVyNi81OEdJCjBvcjUvZm52K2UrNDVMbm45dWYrLy82NjVielA3ZEQ0L1BpajNLVzM1TGpiOGR5NjVidTA0N1o0ekhyUjd0S0UwWWJuOXVqazllU1cKMTVqcTkrdjcvZnRodzJScHgyemE4ZHR3eVhMMi9QYVIxWlA3bFhNNUFBQUFSM1JTVGxNQUFBSkJqTVhvK1FSbjNkNHMwVkgzVXZ4VApNUVhXQm05dzVrbEtsTW5JN1B2ejlOZWlwVmRhQ2V2dkRJR0pEeFJnYUFHMXZoZmw2aHc2Vy81NGpwS2NtSithajRpTGhPY21mY0lBCkFBQUJZa3RIUkFIL0FpM2VBQUFBQjNSSlRVVUg0Z3NhRUNnVEd1bmF4d0FBQVhkSlJFRlVLTTl0MDJkWHdqQVVCdURMQmdVcUlpcUsKNGg2NDZ3YmNTaFR4dWhVVkIwN2NpSHYrZHB1bWxMYjAvWEtUUENkcFQzTUxJTVZvTWx1c05wdlZZallaRFZJa3NqdEtpSnhTaDEySgpUaGRSeGVVc29Kc2ptbkR1UEpaNVNGRTg1UXk5SE5FSjU2Vlk0U082OFZVS1dDVlBGNWZpeTBMaWlSVnhXbTBBdTE5R2xDTk8vWGFvCmtXUjFiWDBqYjNHMlZBc0JOdGhFUmJiWVdnRHF4THFOTzd2SlBRM1dRNUNXZlV3Y0VKTFNZQkFhYURuRUkxS01IbWlrNVJoUDB1bFQKN2JGTklINkNNMFNkRjJxR0ZuYXNLdWNNVzZHTkZ1SEFpOHNydXA2NXZrRzhaZGdPSFoxQ3VidkhCNUxFeDJ3cVE1NHc5eXhhcUF1ZwpXeHpsOEFWZkNYbkQ5dy84WkJ0N2hBL2ZTN2VTcnh4Ky93ZzFpNzkvekVKOTlENzc5YTlzUUx4c2ZsRFBob3lzVFlaSGltMTBMTjlnCjR6YXQyVXlGMWd4SDFCWUpLNXM2cXNhb3F1TmhRbWtCOWU4QS9HVEJwbmdOQWorZHR4bnRqMFJWNnFaWjJSUUkvSno0UE42Z2h3RHoKaEN3b1RJMFFpNEhDRFA5MSs3N29mN0hxYkFBQUFDVjBSVmgwWkdGMFpUcGpjbVZoZEdVQU1qQXhPQzB4TVMweU5sUXhOam8wTURveApPU3N3T0Rvd01PZW5HM2tBQUFBbGRFVllkR1JoZEdVNmJXOWthV1o1QURJd01UZ3RNVEV0TWpaVU1UWTZOREE2TVRrck1EZzZNRENXCitxUEZBQUFBR1hSRldIUlRiMlowZDJGeVpRQkJaRzlpWlNCSmJXRm5aVkpsWVdSNWNjbGxQQUFBQUFCSlJVNUVya0pnZ2c9PSIgLz4KPC9zdmc+Cg==', size: {width: 300, height: 157}}"></bm-marker>-->
                            <!--</template>-->
                        </baidu-map>
                    </div>
                </div>
            </el-col>
        </el-row>
    </div>
</template>
<script>
  import { HomeCommissionStatics } from '@supplier/api/common'
  import PanelGroup from './components/PanelGroup'
  import LineChart from './components/LineChart'

  export default {
    name: 'dashboard-admin',
    components: {
      PanelGroup,
      LineChart
    },
    data() {
      return {
        lineChartData: {
          current_month: [],
          last_month: []
        },
        userData: this.$store.state.user,
        homeData: {
          being_approve_order: null,
          being_send_order: null,
          being_approve_application: null,
          today_commission_total: null,
          today_order_info: {},
          last_order_info: {},
          month_order_info: {},
          last_month_order_info: {}
        },
        search: {
          customer_name: null
        },
        infoWindow: {
          position: { lng: null, lat: null },
          title: null,
          contents: null,
          show: false
        },
        mapList: [],
        center: '郑州市',
        geolocation: null,
        map: {},
        BMap: {},
        mapData: {
          centerPointer: {
            lng: null,
            lat: null
          }
        }
      }
    },
    created() {
      this.getMapList()
    },
    methods: {
      mapChange() {
        if (this.mapList.length > 0) {
          const BMap = this.BMap
          const point = new BMap.Point(this.mapList[0].longitude, this.mapList[0].latitude)
          this.map.centerAndZoom(point, 8)
        } else {
          const point = '郑州市'
          this.map.centerAndZoom(point, 8)
        }
      },
      mapHandler({ BMap, map }) {
        this.map = map
        this.BMap = BMap
        this.center = '郑州市'
        // this.geolocation = new BMap.Geolocation()
        // this.geolocation.getCurrentPosition(function(r) {
        //   if (r) {
        //     _this.center = { lng: r.longitude, lat: r.latitude }
        //   }
        //   _this.initLocation = true
        // }, { enableHighAccuracy: true })
      },
      getMapList() {
        this.mapList = []
        HomeCommissionStatics(this.search).then((response) => {
          this.homeData = response.data.homeCommissionStatics
          if (response.data.homeMap) {
            response.data.homeMap.forEach((val) => {
              this.mapList.push(val)
            })
          }
          this.lineChartData.current_month = this.homeData.current_month
          this.lineChartData.last_month = this.homeData.last_month
          this.lineChartData.last_month = this.homeData.last_month
        }).finally(() => {
        })
      },
      showMarker(marker) {
        this.infoWindow = {
          position: { lng: marker.longitude, lat: marker.latitude },
          title: marker.type,
          contents: marker.name,
          show: true
        }
      },
      setMapCenter(marker) {
        const BMap = this.BMap
        if (marker) {
          const point = new BMap.Point(marker.longitude, marker.latitude)
          this.map.centerAndZoom(point, 12)
        }
      },
      infoWindowClose(e) {
        this.infoWindow.show = false
      },
      infoWindowOpen(e) {
        this.infoWindow.show = true
      }
    }
  }
</script>

<style rel="stylesheet/scss" lang="scss" scoped>
    .dashboard-editor-container {
        padding: 15px;
        background-color: rgb(240, 242, 245);
        overflow-y: auto;
        .project-left_name,
        .project-right_type {
            height: 100px;
            margin-bottom: 20px;
            padding-top: 27px;
            background: #fff;
        }
        .project-left_name {
            padding-left: 21px;
            h4, p {
                margin: 0;
                padding: 0;
            }
            .name {
                margin-bottom: 12px;
                line-height: 22px;
                font-size: 16px;
                font-weight: 500;
                color: #333;
            }
            .describe {
                font-size: 12px;
                color: #999;
            }
        }
        .project-right_type {
            padding-right: 40px;
            padding-left: 40px;
            display: flex;
            justify-content: space-between;
            .card-panel-icon-wrapper {
                float: left;
                cursor: pointer;
                text-align: center;
                .link-type {
                    display: block;
                    width: 72px;
                    height: 68px;
                    padding: 8px;
                    box-sizing: border-box;
                }
                a:hover {
                    background: #fdedd7;
                }
                .card-panel-icon {
                    margin-bottom: 8px;
                    font-size: 28px;
                    color: #F39800;
                }
                p {
                    margin: 0;
                    font-size: 14px;
                    color: #666;
                }
            }
        }
        .achievement {
            .title {
                margin: 0 0 10px;
                font-size: 14px;
                color: #666;
            }
            .card-panel-icon{
                margin-left: 6px;
                cursor: pointer;
            }
            .card-panel-col {
                margin-bottom: 20px;
                &:last-child {
                    .card-panel {
                        background: #F39800;
                        .card-panel-icon-wrapper {
                            border-radius: unset;
                            width: 60px;
                            height: 46px;
                            background: rgba(255, 255, 255, 0.8);
                            color: #F39800;
                            margin-top: 26px;
                            line-height: 46px;
                        }
                        .card-panel-num {
                            color: #fff;
                        }
                        .card-panel-text {
                            color: #fff;
                        }
                    }
                }
            }
            .card-panel {
                height: 100px;
                padding-right: 12px;
                margin-bottom: 10px;
                cursor: pointer;
                font-size: 12px;
                position: relative;
                overflow: hidden;
                color: #666;
                background: #fff;
                box-shadow: 4px 4px 40px rgba(0, 0, 0, .05);
                border: 1px solid #EBECEC;
                &:hover {
                    box-shadow: 0 0 3px #F5A623;
                }
                .icon-people {
                    color: #40c9c6;
                }
                .icon-message {
                    color: #36a3f7;
                }
                .icon-money {
                    color: #f4516c;
                }
                .icon-shoppingCard {
                    color: #34bfa3
                }
                .card-panel-icon-wrapper {
                    float: left;
                    width: 100px;
                    height: 54px;
                    margin: 20px 0 0 8px;
                    color: #333;
                    font-size: 14px;
                    text-align: center;
                    line-height: 54px;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                    em {
                        font-size: 20px;
                        color: #FF5C15;
                        font-style: normal;
                    }
                }
                .card-panel-icon {
                    float: left;
                    font-size: 48px;
                }
                .card-panel-description {
                    float: right;
                    font-weight: bold;
                    margin: 26px 0;
                    .card-panel-text {
                        text-align: right;
                        line-height: 18px;
                        color: #FF5C15;
                        font-size: 18px;
                        margin-bottom: 10px;
                    }
                    .card-panel-num {
                        width: 108px;
                        text-align: right;
                        font-size: 12px;
                        color: #999;
                        overflow: hidden;
                        white-space: nowrap;
                        text-overflow: ellipsis;
                        .card-panel-icon {
                            float: unset;
                            font-size: 12px;
                            color: #FF5C15;
                        }
                        .icon-down {
                            color: #090;
                        }
                    }
                }
            }
        }
        .chart-wrapper-right {
            height: 270px;
            background: #fff;
            padding: 16px 16px 0;
            margin-bottom: 32px;
        }
        .chart-wrapper-left {
            min-height: 270px;
            background: #fff;
            padding: 16px 16px 0;
            margin-bottom: 32px;
        }
        .map-wrapper {
            padding: 12px;
            background: #fff;
            .search-map {
                text-align: right;
                /deep/ .el-form-item__label {
                    color: #abb1b1;
                }
                .title-name {
                    float: left;
                    padding-top: 7px;
                    font-size: 14px;
                    color: #abb1b1;
                }
            }
            .map-wrapper-con {
                padding: 0 12px 12px;
                .bm-view {
                    width: 100%;
                    height: 500px;
                }
            }
            .remark-icon {
                padding-top: 12px;
                padding-left: 12px;
                color: #ffb721;
                .svg-icon {
                    font-size: 22px;
                    vertical-align: sub;
                }
            }
        }

    }
</style>
