<template>
    <div class="app-container">
        <div class="el-card is-always-shadow product-input-content" :style="{height: this.$store.getters.tableHeight + 200 +'px'}">
            <el-row class="height100" v-loading="treeLoading">
                <el-col :span="5" class="height100">
                    <div class="grid-content content-left height100 scroller-y">
                        <tree-level :treeData="brandList"
                                    @clickItem="clickItem"
                                    :addLevel="addBrandOrg"
                                    :brandTitle="brandTitle"
                                    :brandType="brandType"></tree-level>
                    </div>
                </el-col>
                <el-col :span="19" class="height100">
                    <div class="grid-content content-right height100 scroller-y">
                        <div v-if="brandList.length">
                            <div class="title-box bg-white flex-box title-box-top">
                                <div>
                                    <span class="label">当前部门:</span>
                                    <span class="value">{{ currentData.name }}</span>
                                </div>
                                <div v-if="currentData.is_tip || currentLevel == '四级'">
                                    <el-button type="primary"
                                               :disabled="!currentData.status"
                                               @click.stop="addBrandPerson(currentData)"> +新增人员</el-button>
                                </div>
                                <div v-if="(!currentData.is_tip) && currentLevel != '四级'">
                                    <el-button type="primary"
                                               :disabled="!currentData.status"
                                               @click.stop="addBrandOrg(currentData, currentLevel)"> +新增{{ currentLevel }}</el-button>
                                </div>
                            </div>

                            <div class="brand-box bg-white" v-if="currentData.children.length && (!currentData.is_tip) && currentLevel != '四级'">
                                <div class="sub-org" ref="subOrgBox">
                                    <div class="sub-org-box" ref="subOrg">
                                        <span class="sub-org-title">下级部门:</span>
                                        <div class="brand-value top-5 sub-org-wrap">
                                            <span class="sub-org-text"
                                                  v-for="item in currentData.children"
                                                  :key="item.id"
                                                  :title="item.name"
                                                  @click="clickItem(item)">
                                                <el-tag type="info" size="small" class="cursor">
                                                    {{item.name}}
                                                </el-tag>
                                            </span>
                                            <el-button
                                                    class="sub-org-cancel"
                                                    v-show="is_hidden && is_expand"
                                                    type="text"
                                                    @click="loadMore('org')"><i class="el-icon-d-arrow-right arrow-up"></i>收起</el-button>
                                        </div>
                                    </div>
                                    <el-button
                                            v-show="is_hidden && !is_expand"
                                            type="text"
                                            class="sub-org-btn"
                                            @click="loadMore('org')"><i class="el-icon-d-arrow-right arrow-down"></i>更多</el-button>
                                </div>
                            </div>
                            <div class="brand-box bg-white" v-if="list_copy.length && (currentData.is_tip || currentLevel == '四级')">
                                <div class="sub-org" ref="subPersonBox">
                                    <div class="sub-org-box" ref="subPerson">
                                        <span class="sub-org-title">下级员工:</span>
                                        <div class="brand-value top-5 sub-org-wrap">
                                            <span class="sub-org-text"
                                                  v-for="(item, index) in list_copy"
                                                  :key="item.id"
                                                  :title="item.name"
                                                  @click="editDriver(item, index)">
                                                <el-tag type="info" size="small" class="cursor" :class="{'active': clickIndex === index}">
                                                    {{item.name}}
                                                </el-tag>
                                            </span>
                                            <el-button
                                                    class="sub-org-cancel"
                                                    v-show="is_hidden && is_expand"
                                                    type="text"
                                                    @click="loadMore('person')"><i class="el-icon-d-arrow-right arrow-up"></i>收起</el-button>
                                        </div>
                                    </div>
                                    <el-button
                                            v-show="is_hidden && !is_expand"
                                            type="text"
                                            class="sub-org-btn"
                                            @click="loadMore('person')"><i class="el-icon-d-arrow-right arrow-down"></i>更多</el-button>
                                </div>
                            </div>

                            <div class="brand-info-box bg-white">
                                <div class="box-title flex-box">
                                    <div>
                                        基本信息
                                    </div>
                                    <div>
                                        <el-button class="load-btn"
                                                   type="primary"
                                                   :loading="is_add_org"
                                                   @click="updateOrg('OrgForm')"
                                                   v-if="!is_edit_driver">保存</el-button>
                                        <el-button class="load-btn"
                                                   type="primary"
                                                   :loading="is_add_person"
                                                   @click="confirmBrandPerson('personForm',formPersonData,'update')"
                                                   v-else>保存</el-button>
                                    </div>
                                </div>

                                <el-form ref="OrgForm" :model="formData" :rules="orgRules" label-width="130px" class="pd15" v-show="!is_edit_driver">
                                    <el-row :gutter="20">
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="部门名称" prop="name">
                                                    <el-input
                                                            maxlength="15"
                                                            v-model="formData.name"
                                                            placeholder="部门名称最多输入15个字"></el-input>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="部门级别">
                                                    <el-input v-model="formData.rank" disabled></el-input>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="上级部门" v-if="formData.rank !=='三级'">
                                                    <el-input v-model="formData.parent_name" disabled></el-input>
                                                </el-form-item>
                                                <el-form-item label="上级部门" v-if="formData.rank ==='三级'">
                                                    <el-row>
                                                        <el-col :span="12" v-if="formData.parent">
                                                            <el-input v-model="formData.parent.name" disabled></el-input>
                                                        </el-col>
                                                        <el-col :span="12">
                                                            <el-input v-model="formData.parent_name" disabled></el-input>
                                                        </el-col>
                                                    </el-row>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="部门性质" prop="type">
                                                    <el-select v-model="formData.type"
                                                               clearable placeholder="请选择部门性质">
                                                        <el-option label="部门" value="部门"></el-option>
                                                        <el-option label="人员" value="人员"></el-option>
                                                    </el-select>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8" v-if="formData.type === '人员'">
                                            <div class="grid-content">
                                                <el-form-item label="联系电话" prop="phone">
                                                    <el-input v-model="formData.phone" placeholder="请输入联系电话"></el-input>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8" v-if="formData.type === '人员'">
                                            <div class="grid-content">
                                                <el-form-item label="身份证号码" prop="id_card">
                                                    <el-input
                                                            maxlength="18"
                                                            v-model="formData.id_card"
                                                            placeholder="身份证号码为18位，最后可为x"></el-input>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="是否末级" prop="is_tip">
                                                    <el-switch :disabled="(formData.rank ==='三级')||(formData.children && formData.children.length>0)||(formData.children&&!formData.children.length&&list_copy.length>0)"
                                                            v-model="formData.is_tip">
                                                    </el-switch>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="是否启用" prop="status">
                                                    <el-switch
                                                            @change="changeOrgStatus(formData)"
                                                            v-model="formData.status">
                                                    </el-switch>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                    </el-row>
                                </el-form>
                                <el-form ref="personForm" :rules="personRules" :model="formPersonData" label-width="130px" class="pd15" v-show="is_edit_driver">
                                    <el-row :gutter="20">
                                        <el-col :span="8">
                                            <div class="grid-content" prop="name">
                                                <el-form-item label="姓名" prop="name">
                                                    <el-input
                                                            maxlength="15"
                                                            v-model="formPersonData.name"
                                                            placeholder="员工姓名最多输入15个字"></el-input>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="所属部门">
                                                    <el-row>
                                                        <el-col :span="8" v-show="brandList.length">
                                                            <el-select v-model="formPersonData.parent_parent_parent_id"
                                                                       clearable
                                                                       @change="changeLevel('一级', formPersonData.parent_parent_parent_id, 'update')"
                                                                       placeholder="请选择">
                                                                <el-option v-for="item in brandList"
                                                                           :key="item.id"
                                                                           :label="item.name"
                                                                           :value="item.id"></el-option>
                                                            </el-select>
                                                        </el-col>
                                                        <el-col :span="8" v-if="brandList.length">
                                                            <el-select v-model="formPersonData.parent_parent_id"
                                                                       clearable
                                                                       @change="changeLevel('二级', formPersonData.parent_parent_id, 'update')"
                                                                       placeholder="请选择">
                                                                <el-option v-for="item in levelTwo"
                                                                           :key="item.id"
                                                                           :label="item.name"
                                                                           :value="item.id"></el-option>
                                                            </el-select>
                                                        </el-col>
                                                        <el-col :span="8" v-if="brandList.length">
                                                            <el-select v-model="formPersonData.parent_id"
                                                                       clearable
                                                                       placeholder="请选择">
                                                                <el-option v-for="item in levelThree"
                                                                           :key="item.id"
                                                                           :label="item.name"
                                                                           :value="item.id"></el-option>
                                                            </el-select>
                                                        </el-col>
                                                    </el-row>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="身份证号码" prop="id_card">
                                                    <el-input
                                                            maxlength="18"
                                                            v-model="formPersonData.id_card"
                                                            placeholder="身份证号码为18位，最后可为x"></el-input>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="联系电话" prop="telphone">
                                                    <el-input v-model="formPersonData.telphone" placeholder="请输入联系电话"></el-input>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="入职时间" prop="hiredate_on">
                                                    <el-date-picker
                                                            value-format="yyyy-MM-dd hh:mm:ss"
                                                            v-model="formPersonData.hiredate_on"
                                                            type="date"
                                                            placeholder="年-月-日">
                                                    </el-date-picker>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="常用车牌号" prop="license">
                                                    <el-input v-model="formPersonData.license" placeholder="请输入常用车牌号"></el-input>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="初始密码">
                                                    <el-input v-model="formPersonData.password" placeholder="密码修改后，须点击保存才可生效"></el-input>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                        <el-col :span="8">
                                            <div class="grid-content">
                                                <el-form-item label="是否启用" prop="status">
                                                    <el-switch v-model="formPersonData.status"></el-switch>
                                                </el-form-item>
                                            </div>
                                        </el-col>
                                    </el-row>
                                </el-form>
                            </div>

                            <div class="product-list bg-white">
                                <div class="box-title" v-if="is_driver_list">
                                    司机列表
                                </div>
                                <div class="box-title" v-if="!is_driver_list">
                                    <span>配送记录</span>
                                    <div class="fr" v-if="!is_edit_driver">
                                        <el-button type="text" @click="returnDriverList">[返回司机列表]</el-button>
                                    </div>
                                </div>
                                <div class="pd15">
                                    <el-form ref="form" :model="sortData" label-width="90px" class="pdt15 text-right" :inline="true">
                                        <el-form-item label="状态" v-if="is_driver_list">
                                            <el-select v-model="sortData.status"
                                                       clearable
                                                       placeholder="请选择"
                                                       @change="_getDriverList('search')">
                                                <el-option label="已开启" value="已开启"></el-option>
                                                <el-option label="已暂停" value="已暂停"></el-option>
                                            </el-select>
                                        </el-form-item>
                                        <el-form-item prop="name" v-if="is_driver_list">
                                            <el-input v-model="sortData.name"
                                                      placeholder="请输入司机姓名"
                                                      @keyup.enter="_getDriverList('search')"
                                                      @change="_getDriverList('search')"
                                                      clearable></el-input>
                                        </el-form-item>
                                        <el-form-item prop="name" v-if="!is_driver_list">
                                            <el-input v-model="sortData.customer_name"
                                                      placeholder="请输入客户名称"
                                                      @keyup.enter="_getOrderLists(driver_id)"
                                                      @change="_getOrderLists(driver_id)"
                                                      clearable></el-input>
                                        </el-form-item>
                                    </el-form>
                                    <div v-if="is_driver_list">
                                        <el-table
                                                :data="list.items"
                                                v-loading="list.listLoading"
                                                element-loading-text="加载中"
                                                fit
                                                :height="this.$store.getters.tableHeight"
                                                highlight-current-row>
                                            <el-table-column
                                                    type="index"
                                                    label="序号"
                                                    width="50"
                                                    align="center">
                                                <template slot-scope="scope">
                                                    <span>{{scope.$index+1}}</span>
                                                </template>
                                            </el-table-column>
                                            <el-table-column
                                                    align="center"
                                                    label="司机姓名">
                                                <template slot-scope="scope">
                                                    <span>{{scope.row.name}}</span>
                                                </template>
                                            </el-table-column>
                                            <el-table-column
                                                    align="center"
                                                    label="身份证号">
                                                <template slot-scope="scope">
                                                    <span>{{scope.row.id_card}}</span>
                                                </template>
                                            </el-table-column>
                                            <el-table-column
                                                    align="center"
                                                    label="联系电话">
                                                <template slot-scope="scope">
                                                    <span>{{scope.row.mobile}}</span>
                                                </template>
                                            </el-table-column>
                                            <el-table-column
                                                    align="center"
                                                    label="入职时间">
                                                <template slot-scope="scope">
                                                    <span>{{scope.row.hiredate_on | formatDate }}</span>
                                                </template>
                                            </el-table-column>
                                            <el-table-column
                                                    align="center"
                                                    label="常用车牌">
                                                <template slot-scope="scope">
                                                    <span>{{scope.row.license}}</span>
                                                </template>
                                            </el-table-column>
                                            <el-table-column align="center" label="操作" width="220">
                                                <template slot-scope="scope">
                                                    <el-button type="primary" plain @click="editDriverList(scope.row)">修改</el-button>
                                                    <el-button type="primary" plain @click="deliverRecord(scope.row)">查看配送记录</el-button>
                                                </template>
                                            </el-table-column>
                                        </el-table>
                                    </div>
                                    <div v-if="!is_driver_list">
                                        <el-table
                                                :data="orderList.items"
                                                v-loading="orderList.listLoading"
                                                element-loading-text="加载中"
                                                fit
                                                highlight-current-row>
                                            <el-table-column align="center" label="订单编号" width="160">
                                                <template slot-scope="scope">
                                                    <span class="text-warning">{{scope.row.order_number}}</span>
                                                </template>
                                            </el-table-column>
                                            <el-table-column align="center" label="订货时间" width="150">
                                                <template slot-scope="scope">
                                                    <span>{{scope.row.order_at}}</span>
                                                </template>
                                            </el-table-column>
                                            <el-table-column align="center" label="客户名称" width="170">
                                                <template slot-scope="scope">
                                                    <span v-if="scope.row.customer">{{scope.row.customer.store_info ? scope.row.customer.store_info.name : ''}}</span>
                                                </template>
                                            </el-table-column>
                                            <el-table-column align="center" label="订单金额">
                                                <template slot-scope="scope">
                                                    <span>{{scope.row.price}}</span>
                                                </template>
                                            </el-table-column>
                                            <el-table-column align="center" label="到达时间">
                                                <template slot-scope="scope">
                                                    <span class="text-red">{{scope.row.send_at}}</span>
                                                </template>
                                            </el-table-column>
                                            <el-table-column align="center" label="操作" width="220">
                                                <template slot-scope="scope">
                                                    <el-button type="primary" plain @click="viewOrder(scope.row)">查看订单</el-button>
                                                </template>
                                            </el-table-column>
                                        </el-table>
                                        <el-row type="flex" justify="end" class="pagination-container">
                                            <el-pagination background
                                                           @size-change="changeOrderListSize"
                                                           @current-change="changeOrderListCurrentPage"
                                                           :current-page="orderQuery.page"
                                                           :page-sizes="[10, 20, 30, 50]"
                                                           :page-size="orderQuery.limit"
                                                           layout="total, sizes, prev, pager, next, jumper"
                                                           :total="orderList.total">
                                            </el-pagination>
                                        </el-row>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="!brandList.length">
                            <div class="title-box bg-white flex-box">
                                <div>
                                    <span class="label">还未添加组织架构</span>
                                </div>
                            </div>
                            <div class="brand-box">
                                <div class="brand-value">
                                    <el-button type="primary" @click.stop="addBrandOrg(null, '一级')">新增一级</el-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </el-col>
            </el-row>

            <el-dialog
                    v-el-drag-dialog
                    class="order-dialog"
                    title="新建部门"
                    :visible.sync="brandOrgDialog"
                    width="40%">
                <el-form ref="addOrgForm" :model="brandOrgData" label-width="100px" :rules="orgRules">
                    <el-form-item label="部门级别" prop="rank">
                        <el-input type="text" v-model="brandOrgData.rank" disabled></el-input>
                    </el-form-item>
                    <el-form-item label="上级部门" v-if="brandOrgData.rank !=='三级'">
                        <el-input v-model="brandOrgData.parent_name" disabled></el-input>
                    </el-form-item>
                    <el-form-item label="上级部门" v-if="brandOrgData.rank ==='三级'">
                        <el-row>
                            <el-col :span="12" v-if="brandOrgData.parent">
                                <el-input v-model="brandOrgData.parent.name" disabled></el-input>
                            </el-col>
                            <el-col :span="12">
                                <el-input v-model="brandOrgData.parent_name" disabled></el-input>
                            </el-col>
                        </el-row>
                    </el-form-item>
                    <el-form-item label="部门性质" prop="type">
                        <el-select v-model="brandOrgData.type"
                                   clearable placeholder="请选择部门性质">
                            <el-option label="部门" value="部门"></el-option>
                            <el-option label="人员" value="人员"></el-option>
                        </el-select>
                    </el-form-item>
                    <el-form-item label="部门名称" prop="name">
                        <el-input
                                maxlength="15"
                                v-model="brandOrgData.name"
                                placeholder="部门名称最多输入15个字"></el-input>
                    </el-form-item>
                    <el-form-item label="联系电话" prop="mobile" v-if="brandOrgData.type === '人员'">
                        <el-input v-model="brandOrgData.mobile" placeholder="请输入联系电话"></el-input>
                    </el-form-item>
                    <el-form-item label="身份证号码" prop="id_card" v-if="brandOrgData.type === '人员'">
                        <el-input
                                maxlength="18"
                                v-model="brandOrgData.id_card"
                                placeholder="身份证号码为18位，最后可为x"></el-input>
                    </el-form-item>
                    <el-form-item label="是否末级" prop="is_tip">
                        <el-switch :disabled="brandOrgData.rank ==='三级'"
                                v-model="brandOrgData.is_tip">
                        </el-switch>
                    </el-form-item>
                    <el-form-item label="是否启用" prop="status">
                        <el-switch
                                v-model="brandOrgData.status">
                        </el-switch>
                    </el-form-item>
                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="cancelBrandOrg">取 消</el-button>
                    <el-button class="load-btn" type="primary" :loading="is_add_org" @click="confirmBrandOrg('addOrgForm')">确 定</el-button>
                </div>
            </el-dialog>


            <el-dialog
                    v-el-drag-dialog
                    class="order-dialog"
                    :title="brandPersonData.id?'修改员工':'新增员工'"
                    :visible.sync="brandPersonDialog"
                    width="40%"
                    @close="closePersonDialog">
                <el-form ref="personForm" :rules="personRules" :model="brandPersonData" label-width="100px">
                    <el-form-item label="所属部门" v-if="(!brandPersonData.id)&&brandPersonData.parent&&brandPersonData.parent.rank==='一级'">
                        <el-input type="text" v-model="brandPersonData.parent_name" disabled></el-input>
                    </el-form-item>
                    <el-form-item label="所属部门" v-if="(!brandPersonData.id)&&brandPersonData.parent&&brandPersonData.parent.rank==='二级'">
                        <el-row>
                            <el-col :span="12" v-if="brandPersonData.parent">
                                <el-input type="text" v-model="brandPersonData.parent.parent_name" disabled></el-input>
                            </el-col>
                            <el-col :span="12">
                                <el-input type="text" v-model="brandPersonData.parent.name" disabled></el-input>
                            </el-col>
                        </el-row>
                    </el-form-item>
                    <el-form-item label="所属部门" v-if="(!brandPersonData.id)&&brandPersonData.parent&&brandPersonData.parent.rank==='三级'">
                        <el-row>
                            <el-col :span="8" v-if="brandPersonData.parent.parent">
                                <el-input type="text" v-model="brandPersonData.parent.parent.name" disabled></el-input>
                            </el-col>
                            <el-col :span="8" v-if="brandPersonData.parent">
                                <el-input type="text" v-model="brandPersonData.parent.parent_name" disabled></el-input>
                            </el-col>
                            <el-col :span="8">
                                <el-input type="text" v-model="brandPersonData.parent_name" disabled></el-input>
                            </el-col>
                        </el-row>
                    </el-form-item>
                    <el-form-item label="所属部门" v-if="brandPersonData.id">
                        <el-row>
                            <el-col :span="8" v-show="brandList.length">
                                <el-select v-model="brandPersonData.parent_parent_parent_id"
                                           clearable
                                           @change="changeLevel('一级', brandPersonData.parent_parent_parent_id)"
                                           placeholder="请选择">
                                    <el-option v-for="item in brandList"
                                               :key="item.id"
                                               :label="item.name"
                                               :value="item.id"></el-option>
                                </el-select>
                            </el-col>
                            <el-col :span="8" v-if="brandList.length">
                                <el-select v-model="brandPersonData.parent_parent_id"
                                           clearable
                                           @change="changeLevel('二级', brandPersonData.parent_parent_id)"
                                           placeholder="请选择">
                                    <el-option v-for="item in levelTwo"
                                               :key="item.id"
                                               :label="item.name"
                                               :value="item.id"></el-option>
                                </el-select>
                            </el-col>
                            <el-col :span="8" v-if="brandList.length">
                                <el-select v-model="brandPersonData.parent_id"
                                           clearable
                                           placeholder="请选择">
                                    <el-option v-for="item in levelThree"
                                               :key="item.id"
                                               :label="item.name"
                                               :value="item.id"></el-option>
                                </el-select>
                            </el-col>
                        </el-row>
                    </el-form-item>
                    <el-form-item label="员工姓名" prop="name">
                        <el-input
                                maxlength="15"
                                v-model="brandPersonData.name"
                                placeholder="员工姓名最多输入15个字"></el-input>
                    </el-form-item>
                    <el-form-item label="身份证号码" prop="id_card">
                        <el-input
                                maxlength="18"
                                v-model="brandPersonData.id_card"
                                placeholder="身份证号码为18位，最后可为x"></el-input>
                    </el-form-item>
                    <el-form-item label="联系电话" prop="mobile">
                        <el-input v-model="brandPersonData.mobile" placeholder="请输入联系电话"></el-input>
                    </el-form-item>
                    <el-form-item label="入职时间" prop="hiredate_on">
                        <el-date-picker
                                value-format="yyyy-MM-dd hh:mm:ss"
                                v-model="brandPersonData.hiredate_on"
                                type="date"
                                placeholder="年-月-日">
                        </el-date-picker>
                    </el-form-item>
                    <el-form-item label="常用车牌号" prop="license">
                        <el-input v-model="brandPersonData.license" placeholder="请输入常用车牌号"></el-input>
                    </el-form-item>
                    <el-form-item label="初始密码" prop="password" v-if="!brandPersonData.id">
                        <el-input v-model="brandPersonData.password" placeholder="请输入初始密码"></el-input>
                    </el-form-item>
                    <el-form-item label="初始密码" v-if="brandPersonData.id">
                        <el-input v-model="brandPersonData.password" placeholder="密码修改后，须点击保存才可生效"></el-input>
                    </el-form-item>
                    <el-form-item label="是否启用" prop="status">
                        <el-switch
                                v-model="brandPersonData.status">
                        </el-switch>
                    </el-form-item>
                </el-form>
                <div slot="footer" class="dialog-footer">
                    <el-button @click="cancelBrandPerson">取 消</el-button>
                    <el-button class="load-btn" type="primary" :loading="is_add_person" @click="confirmBrandPerson('personForm',brandPersonData,'store')">确 定</el-button>
                </div>
            </el-dialog>
        </div>
    </div>
</template>

<script>
  import TreeLevel from "@supplier/components/treeLevel"
  import { AddOrg, GetOrgList, AddDriver, DriverList } from '../../api/driver'
  import { listHelper } from '@common/utils/listHelper'
  import { GetOrderLists } from '../../api/order'
  import { notify } from '@common/utils/helper'
  import elDragDialog from '@supplier/directive/el-dragDialog'
  export default {
    name: 'personDriver',
    directives: { elDragDialog },
    components: { TreeLevel },
    data() {
      var validateTel = (rule, value, callback) => {
        if (!value) {
          // 验证空手机号
          callback(new Error('请输入手机号'))
        } else if (!value && rule.field === 'emergency_phone') {
          // 不验证紧空急联系人电话
          callback()
        } else {
          if (!(/^1[345678]\d{9}$/.test(value))) {
            callback(new Error('请输入正确的联系电话'))
          }
          callback()
        }
      }
      var validateIdCard = (rule, value, callback) => {
        if (!value) {
          callback()
        } else {
          if (!(/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/.test(value))) {
            callback(new Error('请输入正确的身份证号'))
          }
          callback()
        }
      }
      return {
        validateTel,
        validateIdCard,
        orgRules: {
          phone: [
            { required: true, message: '请输入联系电话', trigger: 'blur' },
            { validator: validateTel, trigger: ['blur'] }
          ],
          mobile: [
            { required: true, message: '请输入联系电话', trigger: 'blur' },
            { validator: validateTel, trigger: ['blur'] }
          ],
          id_card: [
            { validator: validateIdCard, trigger: ['blur'] }
          ],
          name: [
            { required: true, message: '请输入部门名称', trigger: 'blur' }
          ],
          type: [
            { required: true, message: '请选择部门性质', trigger: 'change' }
          ],
          is_tip: [
            { required: true, message: '请选择是否末级' }
          ],
          status: [
            { required: true, message: '请选择是否启用' }
          ]
        },
        personRules: {
          name: [
            { required: true, message: '请输入员工姓名', trigger: 'blur' }
          ],
          telphone: [
            { required: true, message: '请输入联系电话', trigger: 'blur' },
            { validator: validateTel, trigger: ['blur'] }
          ],
          mobile: [
            { required: true, message: '请输入联系电话', trigger: 'blur' },
            { validator: validateTel, trigger: ['blur'] }
          ],
          id_card: [
            { validator: validateIdCard, trigger: ['blur'] }
          ],
          hiredate_on: [
            { required: true, message: '请选择入职时间', trigger: 'blur' }
          ],
          license: [
            { required: true, message: '请输入车牌号', trigger: 'blur' }
          ],
          password: [
            { required: true, message: '请输入初始密码', trigger: 'blur' }
          ],
          status: [
            { required: true, message: '请输入初始密码' }
          ]
        },
        formData: {},
        formPersonData: {},
        sortData: {
          status: '',
          name: '',
          customer_name: ''
        },
        formLabelWidth: '120px',
        brandOrgDialog: false,
        brandPersonDialog: false,
        brandList: [],
        brandTitle: '组织架构',
        brandType: '部门',
        is_driver_list: true,
        brandOrgData: {
          rank: '',
          type: '',
          name: '',
          is_tip: false,
          status: true,
          parent_id: 0,
          mobile: null,
          id_card: null
        },
        brandPersonData: {
          name: '',
          id_card: '',
          mobile: '',
          license: '',
          status: true,
          supplier_org_id: '',
          hiredate_on: '',
          password: '123456789a0'
        },
        currentLevel: '一级',
        currentData: {
          id: '',
          name: '',
          rank: '',
          children: [],
          parent_id: '',
          is_tip: false,
          status: null,
          type: ''
        },
        brand_level: ['一级', '二级', '三级', '四级'],
        list: {
          items: null,
          total: null,
          listLoading: true
        },
        list_copy: [],
        listQuery: {
          page: 1,
          sort: '-id'
        },
        orderList: {
          items: null,
          total: null,
          listLoading: true
        },
        orderQuery: {
          page: 1,
          limit: 10,
          sort: '-id'
        },
        driver_id: '',
        driver_org: [],
        is_edit_driver: false,
        levelTwo: [],
        levelThree: [],
        clickIndex: -1,
        is_hidden: false,
        is_expand: false,
        is_add_org: false,
        is_add_person: false,
        treeLoading: false
      }
    },
    component: {
      TreeLevel
    },
    created() {
      this.treeLoading = true
      GetOrgList(true, '司机').then((result) => {
        this.brandList = result.data.supplierOrgList
        if (this.brandList.length) {
          this.currentData = this.brandList[0]
          this.$set(this.currentData, 'parent_name', '无')
          this.formData = this._.clone(this.currentData)
          this.$set(this.formData, 'phone', this.formData.mobile)
          this.initSubOrg('org')
          this._getDriverList()
        }
      }).finally(() => {
        this.treeLoading = false
      })
    },
    methods: {
      initSubOrg(type) {
        this.is_hidden = false
        this.is_expand = false
        const self = this
        setTimeout(() => {
          if (type === 'org') {
            if (self.$refs.subOrgBox) {
              if (self.$refs.subOrg.offsetHeight > 132) {
                self.is_hidden = true
                self.is_expand = false
                self.$refs.subOrgBox.style.height = '132px'
                self.$refs.subOrgBox.style.overflowY = 'hidden'
              } else {
                self.$refs.subOrgBox.style.height = 'auto'
                self.is_hidden = false
              }
            }
          } else {
            if (self.$refs.subPersonBox) {
              if (self.$refs.subPerson.offsetHeight > 132) {
                self.is_hidden = true
                self.is_expand = false
                self.$refs.subPersonBox.style.height = '132px'
                self.$refs.subPersonBox.style.overflowY = 'hidden'
              } else {
                self.$refs.subPersonBox.style.height = 'auto'
                self.is_hidden = false
              }
            }
          }
        }, 200)
      },
      // 列表每页条数调整
      changeListSize(value) {
        this.listQuery.limit = value
        this._getDriverList()
      },
      // 列表当前页码变更
      changeListCurrentPage(value) {
        this.listQuery.page = value
        this._getDriverList()
      },
      // 列表每页条数调整
      changeOrderListSize(value) {
        this.orderQuery.limit = value
        this._getOrderLists(this.driver_id, 'page')
      },
      // 列表当前页码变更
      changeOrderListCurrentPage(value) {
        this.orderQuery.page = value
        this._getOrderLists(this.driver_id, 'page')
      },
      viewDetail(data) {
        this.is_driver_list = false
      },
      viewOrder(row) {
        this.$router.push({ name: 'order.detail', params: { id: row.id }})
      },
      deliverRecord(data) {
        this.is_driver_list = false
        this.driver_id = data.id
        this.sortData.customer_name = ''
        this._getOrderLists(this.driver_id)
      },
      addBrandPerson(item) {
        this.brandPersonDialog = true
        setTimeout(() => {
          this.$refs['personForm'].resetFields()
        }, 50)
        this.brandPersonData = {
          name: '',
          id_card: '',
          mobile: '',
          license: '',
          status: true,
          supplier_org_id: '',
          hiredate_on: '',
          password: '123456789a0'
        }
        this.$set(this.brandPersonData, 'parent', item)
        this.brandPersonData.parent_name = item.name
        this.brandPersonData.supplier_org_id = item.id
      },
      cancelBrandPerson() {
        this.brandPersonData = {
          name: '',
          id_card: '',
          mobile: '',
          license: '',
          status: true,
          supplier_org_id: '',
          hiredate_on: '',
          password: '123456789a0'
        }
        this.brandPersonDialog = false
      },
      filterPersonParams(data) {
        if (data.id) {
          if (data.parent_id) {
            data.supplier_org_id = data.parent_id
          } else if (data.parent_parent_id) {
            data.supplier_org_id = data.parent_parent_id
          } else if (data.parent_parent_parent_id) {
            data.supplier_org_id = data.parent_parent_parent_id
          }
        }
        const addPersonParams = {
          name: data.name,
          id_card: data.id_card,
          status: data.status,
          supplier_org_id: data.supplier_org_id,
          mobile: data.telphone ? data.telphone : data.mobile,
          license: data.license,
          password: data.password,
          hiredate_on: data.hiredate_on
        }
        if (data.id) {
          addPersonParams.id = data.id
        }
        return addPersonParams
      },
      confirmBrandPerson(ref, data, action) {
        this.$refs[ref].validate((valid) => {
          if (valid) {
            let personParams = {}
            personParams = this.filterPersonParams(data)
            this.is_add_person = true
            AddDriver(personParams).then((result) => {
              if (personParams.id) {
                notify.success('修改员工成功')
                this.brandPersonDialog = false
                this._getDriverList('edit', personParams.id)
              } else {
                notify.success('新增员工成功')
                if (this.clickIndex !== -1) {
                  this.clickIndex += 1
                }
                this.brandPersonDialog = false
                this.sortData = {
                  status: '',
                  name: '',
                  customer_name: ''
                }
                this._getDriverList()
              }
            }).finally(() => {
              this.is_add_person = false
            })
          } else {
            return false
          }
        })
      },
      addBrandOrg(item, level) {
        this.brandOrgDialog = true
        setTimeout(() => {
          this.$refs['addOrgForm'].resetFields()
        }, 50)
        this.brandOrgData.rank = level
        this.currentLevel = level
        if (level === '三级') {
          this.brandOrgData.is_tip = true
        } else {
          this.brandOrgData.is_tip = false
        }
        if (item) {
          if (item.parent) {
            this.$set(this.brandOrgData, 'parent', item.parent)
          }
          this.brandOrgData.parent_name = item.name
          this.brandOrgData.parent_id = item.id
        } else {
          this.brandOrgData = {
            id: '',
            name: '',
            rank: '',
            children: [],
            parent_id: '',
            is_tip: false,
            status: true,
            mobile: null,
            id_card: null
          }
          this.brandOrgData.rank = '一级'
          this.brandOrgData.parent_name = '无'
          this.brandOrgData.parent_id = 0
        }
      },
      cancelBrandOrg() {
        this.brandOrgData = {
          rank: '',
          type: '',
          name: '',
          is_tip: false,
          status: true,
          parent_id: 0,
          mobile: null,
          id_card: null
        }
        this.brandOrgDialog = false
      },
      findCurrentBrant(level, id, data, action) {
        let curIdx
        const that = this
        if (level === 1) {
          curIdx = that._.findIndex(that.brandList, function(o) { return o.id === id })
          if (curIdx !== -1) {
            if (action === 'update') {
              that._.forIn(data, (val, key) => {
                if (key !== 'children') {
                  that.brandList[curIdx][key] = val
                } else {
                  if (that.brandList[curIdx]['children']) {
                    that.brandList[curIdx]['children'] = val
                  }
                }
              })
            } else {
              that.brandList[curIdx].children.push(data)
            }
          }
        } else if (level === 2) {
          that._.each(that.brandList, function(item, index) {
            curIdx = that._.findIndex(item.children, function(o) { return o.id === id })
            if (curIdx !== -1) {
              if (action === 'update') {
                that._.forIn(data, (val, key) => {
                  if (key !== 'children') {
                    item.children[curIdx][key] = val
                  }
                })
              } else {
                item.children[curIdx].children.push(data)
              }
            }
          })
        } else if (level === 3) {
          that._.each(that.brandList, function(item, index) {
            if (item.children && item.children.length) {
              that._.each(item.children, function(obj, i) {
                curIdx = that._.findIndex(obj.children, function(o) { return o.id === id })
                if (curIdx !== -1) {
                  if (action === 'update') {
                    that._.forIn(data, (val, key) => {
                      if (key !== 'children') {
                        obj.children[curIdx][key] = val
                      }
                    })
                  } else {
                    obj.children[curIdx].children.push(data)
                  }
                }
              })
            }
          })
        }
      },
      confirmBrandOrg(ref) {
        if (this.brandOrgData.type === '部门') {
          this.brandOrgData.mobile = null
          this.brandOrgData.id_card = null
        }
        this.$refs[ref].validate((valid) => {
          if (valid) {
            this.$set(this.brandOrgData, 'category', '司机')
            this.is_add_org = true
            AddOrg(this.brandOrgData).then((result) => {
              if (this.currentLevel === '一级') {
                if (!this.brandList.length) {
                  this.currentData = result.data.storeSupplierOrg
                  this.$set(this.currentData, 'parent_name', '无')
                  this.formData = this._.clone(this.currentData)
                  this.$set(this.formData, 'phone', this.formData.mobile)
                  this.list.listLoading = false
                }
                this.brandList.push(result.data.storeSupplierOrg)
              } else if (this.currentLevel === '二级') {
                this.findCurrentBrant(1, this.brandOrgData.parent_id, result.data.storeSupplierOrg)
              } else if (this.currentLevel === '三级') {
                this.findCurrentBrant(2, this.brandOrgData.parent_id, result.data.storeSupplierOrg)
              }
              this.brandOrgData = {
                rank: '',
                type: '',
                name: '',
                is_tip: false,
                status: true,
                parent_id: 0,
                mobile: null,
                id_card: null
              }
              this.brandOrgDialog = false
              notify.success('添加部门成功')
            }).finally(() => {
              this.is_add_org = false
            })
          } else {
            return false
          }
        })
      },
      clickItem(item) {
        this.is_edit_driver = false
        this.is_driver_list = true
        this.clickIndex = -1
        if (!item) {
          this.currentLevel = '一级'
          this.$set(this.currentData, 'parent_name', '无')
          this.formData = this._.clone(this.currentData)
        } else {
          this.currentData = this._.cloneDeep(item)
          if (item.rank === '一级') {
            this.$set(this.currentData, 'parent_name', '无')
          } else if (item.rank === '二级') {
            this.$set(this.currentData, 'parent_name', item.parent.name)
          } else if (item.rank === '三级') {
            this.$set(this.currentData, 'parent_name', item.parent.name)
            this.$set(this.currentData, 'parent', item.parent.parent)
          }
          if (item.children && item.children.length) {
            this.initSubOrg('org')
          }
          this.formData = this._.clone(this.currentData)
          this.currentLevel = this.brand_level[item._level]
          this.sortData.name = ''
          this._getDriverList()
        }
        this.$set(this.formData, 'phone', this.formData.mobile)
      },
      _getDriverList(action, driver_id) {
        if (!action) {
          this.sortData = {
            status: '',
            name: '',
            customer_name: ''
          }
        }
        const more = {
          supplier_org_id: this.currentData.id,
          name: this.sortData.name,
          status: this.sortData.status ? this.sortData.status : '不限'
        }
        this.list.listLoading = true
        DriverList(more).then(response => {
          this.list.items = response.data.driverList
          if (!action) {
            this.list_copy = this._.cloneDeep(response.data.driverList)
          } else if (action === 'edit') {
            if (this.is_edit_driver) {
              this._.each(this.formPersonData, (val, key) => {
                this.$set(this.list_copy[this.clickIndex], key, val)
              })
            } else {
              this._.each(response.data.driverList, (o) => {
                if (o.id === driver_id) {
                  const dIdx = this._.findIndex(this.list_copy, (obj) => { return obj.id === driver_id })
                  this.list_copy[dIdx] = this._.cloneDeep(o)
                }
              })
            }
          }
          this.list.listLoading = false
          if ((!this.currentData.children) || (this.currentData.children && !this.currentData.children.length && this.list_copy.length)) {
            this.initSubOrg('person')
          }
        })
      },
      updateOrg(ref) {
        if (this.formData.children.length && this.formData.is_tip) {
          notify.warning('该部门下有子部门，禁止设置为末级')
          this.formData.is_tip = false
          return false
        }
        if (!this.formData.children.length && !this.formData.is_tip && this.list_copy.length) {
          notify.warning('该部门下有在职人员，禁止设置为非末级')
          this.formData.is_tip = true
          return false
        }
        if (this.formData.type === '部门') {
          this.formData.mobile = null
          this.formData.phone = null
          this.formData.id_card = null
        }
        this.formData.mobile = this.formData.phone
        this.$refs[ref].validate((valid) => {
          if (valid) {
            this.is_add_org = true
            AddOrg(this.formData).then((result) => {
              if (this.formData.rank === '一级') {
                this.findCurrentBrant(1, this.formData.id, result.data.storeSupplierOrg, 'update')
              } else if (this.formData.rank === '二级') {
                this.findCurrentBrant(2, this.formData.id, result.data.storeSupplierOrg, 'update')
              } else if (this.formData.rank === '三级') {
                this.findCurrentBrant(3, this.formData.id, result.data.storeSupplierOrg, 'update')
              }
              this.$set(this.currentData, 'type', this.formData.type)
              this.$set(this.currentData, 'is_tip', this.formData.is_tip)
              this.$set(this.currentData, 'status', this.formData.status)
              this.$set(this.currentData, 'name', this.formData.name)
              this.$set(this.currentData, 'mobile', this.formData.mobile)
              this.$set(this.currentData, 'id_card', this.formData.id_card)
              notify.success('修改部门成功')
            }).finally(() => {
              this.is_add_org = false
            })
          } else {
            return false
          }
        })
      },
      _findOrg(id, arr) {
        const returnData = []
        if (this._.findIndex(arr, function(obj) { return obj.id === id }) !== -1) {
          returnData.push(arr[this._.findIndex(arr, function(obj) { return obj.id === id })])
          this.levelTwo = arr[this._.findIndex(arr, function(obj) { return obj.id === id })].children
        } else {
          this._.each(arr, (item, index) => {
            if (item.children && item.children) {
              if (this._.findIndex(item.children, function(obj) { return obj.id === id }) !== -1) {
                returnData.push(item)
                returnData.push(item.children[this._.findIndex(item.children, function(obj) { return obj.id === id })])
                this.levelTwo = item.children
                this.levelThree = item.children[this._.findIndex(item.children, function(obj) { return obj.id === id })].children
              } else {
                this._.each(item.children, (val, key) => {
                  if (val.children && val.children.length) {
                    if (this._.findIndex(val.children, function(obj) { return obj.id === id }) !== -1) {
                      returnData.push(item)
                      returnData.push(val)
                      returnData.push(val.children[this._.findIndex(val.children, function(obj) { return obj.id === id })])
                      this.levelTwo = item.children
                      this.levelThree = val.children
                    }
                  }
                })
              }
            }
          })
        }
        return returnData
      },
      editDriver(data, index) {
        this.driver_org = this._findOrg(data.supplier_org_id, this.brandList)
        this.$set(data, 'parent_id', this.driver_org[2] ? this.driver_org[2].id : '')
        this.$set(data, 'parent_parent_id', this.driver_org[1] ? this.driver_org[1].id : '')
        this.$set(data, 'parent_parent_parent_id', this.driver_org[0].id)
        if (index !== -1) {
          this.clickIndex = index
          this.is_edit_driver = true
          this.formPersonData = this._.clone(data)
          this.$set(this.formPersonData, 'telphone', data.mobile)
          this.is_driver_list = false
          this.driver_id = data.id
          this.sortData.customer_name = ''
          this._getOrderLists(this.driver_id)
        } else {
          this.brandPersonData = this._.clone(data)
        }
      },
      editDriverList(data) {
        this.editDriver(data, -1)
        this.brandPersonDialog = true
      },
      changeLevel(level, id, action) {
        if (level === '一级') {
          if (!id) {
            this.levelTwo = []
            this.levelThree = []
          } else {
            this.levelTwo = this.brandList[this._.findIndex(this.brandList, function(obj) { return obj.id === id })].children
            this.levelThree = []
          }
          if (action) {
            this.formPersonData.parent_id = ''
            this.formPersonData.parent_parent_id = ''
          } else {
            this.brandPersonData.parent_id = ''
            this.brandPersonData.parent_parent_id = ''
          }
        } else if (level === '二级') {
          if (!id) {
            this.levelThree = []
          } else {
            this.levelThree = this.levelTwo[this._.findIndex(this.levelTwo, function(obj) { return obj.id === id })].children
          }
          if (action) {
            this.formPersonData.parent_id = ''
          } else {
            this.brandPersonData.parent_id = ''
          }
        }
      },
      _getOrderLists(driver_id, action) {
        const params = {
          driver_id: driver_id,
          customer_name: this.sortData.customer_name
        }
        if (!action) {
          this.orderQuery.page = 1
        }
        this.orderList.listLoading = true
        GetOrderLists(this.orderQuery, params).then(response => {
          listHelper.setList(this.orderList, this.orderQuery, response.data.orderPaginator)
          this.orderList.listLoading = false
        })
      },
      closePersonDialog() {
        if (this.is_edit_driver) {
          return false
        }
        this.is_edit_driver = false
      },
      changeOrgStatus(data) {
        let msg = ''
        if (data.status) {
          msg = '是否确认开启当前部门（下级部门将同步开启）?'
        } else {
          msg = '是否确认禁用当前部门（禁用后当前部门不能添加子部门，下级部门将同步禁用）?'
        }
        this.$confirm(msg, '提示', {
          confirmButtonText: '确认',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          return false
        }).catch(() => {
          data.status = !data.status
        })
      },
      returnDriverList() {
        this.is_driver_list = true
      },
      loadMore(type) {
        this.is_expand = !this.is_expand
        const me = this
        if (type === 'org') {
          if (me.$refs.subOrgBox) {
            me.$refs.subOrgBox.style.height = (this.is_expand ? 'auto' : '132px')
          }
        } else {
          if (me.$refs.subPersonBox) {
            me.$refs.subPersonBox.style.height = (this.is_expand ? 'auto' : '132px')
          }
        }
      }
    },
    filters: {
      formatDate: (date) => {
        const values = date
        if (values.length >= 10) {
          return values.slice(0, 10)
        } else {
          return values
        }
      }
    }
  }
</script>
<style lang="scss" scoped>
    .height100{
        height: 100%;
    }
    .top-5{
        margin-top: 5px;
    }
    .scroller-y{
        overflow-y: auto;
    }
    .arrow-down{
        transform:rotate(90deg);
        -ms-transform:rotate(90deg); 	/* IE 9 */
        -moz-transform:rotate(90deg); 	/* Firefox */
        -webkit-transform:rotate(90deg); /* Safari 和 Chrome */
        -o-transform:rotate(90deg);
    }
    .arrow-up{
        transform:rotate(-90deg);
        -ms-transform:rotate(-90deg); 	/* IE 9 */
        -moz-transform:rotate(-90deg); 	/* Firefox */
        -webkit-transform:rotate(-90deg); /* Safari 和 Chrome */
        -o-transform:rotate(-90deg);
    }
    .product-input-content {
        .pd15 {
            padding: 15px;
        }
        .pdr15 {
            padding-right: 15px;
        }
        .pdt15 {
            padding-top: 15px;
        }
        .pdr20 {
            padding-right: 20px;
        }
        .text-right{
            text-align: right;
        }
        .content-left {
            padding: 10px 15px 15px 15px;
        }
        .content-right {
            background-color: #fcfcfc;
            padding: 0 0 0 10px;
        }
        .level-top-title {
            padding: 14px 0;
            .level-title-left {

            }
            .level-title-right {
            }
        }
        .level-title {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            margin-left: -15px;
            margin-right: -15px;
            padding-left: 15px;
            padding-right: 15px;
        }
        .brand-value {
            display: inline-block;
            .el-tag{
                margin-left: 5px;
                margin-bottom: 10px;
                max-width: 99%;
            }
        }
        .box-title,.title-box {
            padding: 10px 15px;
            border-bottom: 1px solid #eeeeee;
        }
        .bg-white {
            background-color: #fff;
        }

        .brand-box {
            padding: 10px 15px;
            margin-bottom: 10px;
            min-height: 60px;
        }
        .brand-info-box{
            margin-bottom: 10px;
        }
        .title-box-top{
            padding-bottom: 9px;
        }
        .brand-info-box.title-box{
            padding: 0;
            margin-bottom:0;
        }
        .el-select {
            width: 100%;
        }
        .next-level-box{
            .el-tag--mini{
                margin: 3px;
            }
        }
        .level-title-one{
            background-color: rgba(59, 52, 44,.7);
            color: #fff;
        }
        .level-title-two{
            color: #F39800;
        }
        .children-brand{
            padding-bottom: 15px;
        }
        .flex-box{
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
        }
        .el-button{
            cursor: pointer;
        }
        .el-date-editor.el-input{
            width: 100%;
        }
        .cursor{
            cursor: pointer;
        }
        .el-tag.active{
            color: #fff;
            background: #F39800;
        }
    }
    .sub-org{
        position: relative;
        overflow-x: hidden;
        word-break: break-all;
        padding-right: 38px;
        &-btn{
            position: absolute;
            bottom: 3px;
            right: 0;
        }
        &-wrap{
            max-width: 100%;
        }
        &-text{
            display: inline-block;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        &-title{
            float: left;
            margin-top: 7px;
        }
        &-cancel{
            float: right;
        }
    }
</style>
