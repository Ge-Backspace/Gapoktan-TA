<template>
    <!-- Sidenav -->
    <nav class="sidenav navbar navbar-vertical  fixed-left  navbar-expand-xs navbar-light bg-white" id="sidenav-main">
        <div class="scrollbar-inner">
            <!-- Brand -->
            <div class="sidenav-header  d-flex  align-items-center">
                <a class="navbar-brand">
                    <img src="~assets/img/Afsen-Logo.png" class="navbar-brand-img" alt="...">
                </a>
                <div class=" ml-auto ">
                    <!-- Sidenav toggler -->
                    <div class="sidenav-toggler2 d-xl-block" @click="toggle()" data-target="#sidenav-main" style="margin-right: 16px;cursor: pointer">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="navbar-inner">
                <!-- Collapse -->
                <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                    <!-- Nav items -->
                    <ul class="navbar-nav">

                        <li class="nav-item" v-for="(menu, idx) in menus" :key="'m1-'+idx">
                            <router-link v-if="typeof menu.children == 'undefined'"
                                 v-bind:to="menu.route">
                                <a class="nav-link" role="button">
                                    <el-tooltip class="item" effect="dark" :content="menu.text" placement="right">
                                        <i v-bind:class="bindString(menu.icon)"></i>
                                    </el-tooltip>
                                    <span class="nav-link-text">{{menu.text}}</span>
                                </a>
                            </router-link>
                        </li>

                        <li class="nav-item" v-for="(menu, idx) in menus" :key="idx">
                            <a class="nav-link"
                               v-bind:href="'#navbar-' + idx"
                               onclick="return false"
                               data-toggle="collapse"
                               role="button"
                               aria-expanded="false"
                               aria-controls="navbar-components"
                                v-if="typeof menu.children != 'undefined'">
                                <el-tooltip class="item" effect="dark" v-bind:content="menu.text" placement="right">
                                    <i v-bind:class="bindString(menu.icon)"></i>
                                </el-tooltip>
                                <span class="nav-link-text">{{menu.text}}</span>
                            </a>
                            <div class="collapse" v-bind:id="'navbar-' + idx">
                                <ul class="nav nav-sm flex-column">
                                    <li class="nav-item" v-for="(child, idc) in menu.children" :key="'m2-'+idc">
                                        <router-link v-bind:to="child.route">
                                            <a class="nav-link">
                                                <el-tooltip class="item" effect="dark" v-bind:content="child.text" placement="right">
                                                    <span class="sidenav-mini-icon">
                                                        <i v-bind:class="bindString(child.icon)"></i>
                                                    </span>
                                                </el-tooltip>
                                                <span class="sidenav-normal"> {{child.text}} </span>
                                            </a>
                                        </router-link>
                                    </li>
                                </ul>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
    import {mapMutations} from 'vuex';
    import {menu} from "./MenuUser";

    export default {
        data() {
            return {
                collapse: true,
                menus: []
            }
        },
        mounted() {
            this.menus = menu();
        },
        methods: {
            ...mapMutations({
                toggle: "drawer/toggle"
            }),
            bindString(cls) {
                return cls;
            }
        },
    }

</script>

<style lang="scss" scoped>
    @media screen and (max-device-width: 640px),
    screen and (max-width: 640px) {
        .sidenav-toggler-inner {
            margin-right: 30px;
        }
    }

</style>
