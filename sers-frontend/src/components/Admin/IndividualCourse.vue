<template>
    <div class="container-fluid  display-container">
        <div class="back">
            <div id="backbutton">
                <span class="arrow primera next"><button type='button' @click="goBack()"><i class="fas fa-long-arrow-alt-left"></i></button></span>
            </div>
        </div>
        <div class="table-container" v-if="$store.state.role == 2">
            <div class="table-header">
                <h4><i class="fas fa-edit"></i> Edit Run Details</h4>
                <br/>
                <h5>{{selectedCourse.Course_ID}} - {{selectedCourse.Course_Name}}</h5>
                <form>
                    
                    <label for="instructor" >Instructor</label>
                    
                    <select class="form-control" id="instructor"  v-model="current_idx" @change="addInstructor(current_idx)">
                        <option id="default" value="" disabled selected>Select instructor(s) to assign them to the course</option>
                        <option id="selection" v-for="(instructor, idx) in instructors" :key="instructor" :value="idx">{{instructor.User_Email}}</option>
                    </select>
                    <span class="selected-instructor" v-for="(instructor, idx) in instructor_list" :key="idx">{{instructor.User_Email}} <i @click="removeInstructor(idx)" class="fas fa-times-circle"></i></span>
                    <br/>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <label for="start">Start date:</label>
                                <input type="date" id="start"
                                    v-model="new_values.Run_StartDate">
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <label for="start">End date:</label>
                                <input type="date" id="end"
                                    v-model="new_values.Run_EndDate"
                                    :min="new_values.Run_StartDate">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <label for="appt">Start Time:</label>
                                <input type="time" id="start-time"
                                    v-model="new_values.Run_StartTime"
                                    min="00:00" max="24:00" format="HH:mm" required>
                            </div>

                            <div class="col-md-12 col-lg-6">
                                <label for="appt">End Time:</label>
                                <input type="time" id="end-time"
                                    v-model="new_values.Run_EndTime"
                                    min="00:00" max="24:00" required>
                            </div>
                        </div>                        

                        <div class="row">
                            <div class="col-md-12 col-lg-12">                                        
                                <label for='days'>Course Duration:</label>
                                <input type="number" id="days" v-model="new_values.Run_Days">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <label for='minslots'>Minimum slots:</label>
                                <input type="number" id="minslots"  v-model="new_values.Run_MinSlots">
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <label for='maxslots'>Maximum Slots:</label>
                                <input type="number" id="maxslots" v-model="new_values.Run_MaxSlots">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <label for='venue'>Venue:</label>
                                <input type="text" id="venue" v-model="new_values.Run_Venue">
                            </div>
                            <div class="col-md-12 col-lg-6">
                                <label for='fees'>Fee: ($)</label>
                                <input type="number" id="fees" v-model="new_values.Run_Fees">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                            <label for="course-desc">Course Description</label>
                            <textarea class="form-control" rows="10" id="course-desc" placeholder="Course Description🖊️" v-model="new_values.Run_Desc"></textarea>
                            </div>
                        </div>

                    </div>
                    <br/>
                    <button type="button" class="btn btn-primary" @click="update()">Update Run</button> 
                </form>
            </div>
        </div>
        <div class="table-container" v-if="$store.state.role == 3">
            <div class="table-header">
                <h4><i class="fas fa-edit"></i> Edit Run Details</h4>
                <br/>
                <h5>{{selectedCourse.Course_ID}} - {{selectedCourse.Course_Name}}</h5>
                <form>
                    
                    <label for="instructor" >Instructor</label>
                    
                    <select class="form-control" id="instructor"  v-model="current_instructor" disabled>
                        <option value="" disabled selected v-if="old_instructor.length>0">current: {{old_instructor}}</option>
                        <option v-for="instructor in instructors" :key="instructor" :value="instructor.User_ID">{{instructor.User_Email}}</option>
                    </select>
                    <span class="selected-instructor" v-for="(instructor, idx) in instructor_list" :key="idx">{{instructor.User_Email}} </span>
                    <br/>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6 col-xs-12">
                                <label for="start">Start date:</label>
                                <input type="date" id="start"
                                    v-model="new_values.Run_StartDate">
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <label for="start">End date:</label>
                                <input type="date" id="end"
                                    v-model="new_values.Run_EndDate"
                                    :min="new_values.Run_StartDate">
                            </div>
                        </div>
                    </div>
         
                    <div class="form-group">
                        <label for="appt">Start Time:</label>
                        <input type="time" id="start-time"
                            v-model="new_values.Run_StartTime"
                            min="00:00" max="24:00" format="HH:mm" required>

                        <label for="appt">End Time:</label>
                        <input type="time" id="end-time"
                            v-model="new_values.Run_EndTime"
                            min="00:00" max="24:00" required>
                    </div>

                    <div class="form-group">
                        <label for='days'>Course Duration:</label>
                        <input type="number" id="days" v-model="new_values.Run_Days">
                    </div>
                    
                    <div class="form-group">
                        <label for='minslots'>Minimum slots:</label>
                        <input type="number" id="minslots"  v-model="new_values.Run_MinSlots">

                        <label for='maxslots'>Maximum Slots:</label>
                        <input type="number" id="maxslots" v-model="new_values.Run_MaxSlots">
                    </div>

                    <div class="form-group">
                        <label for='venue'>Venue:</label>
                        <input type="text" id="venue" v-model="new_values.Run_Venue" disabled>
                        <label for='fees'>Fee: ($)</label>
                        <input type="number" id="fees" v-model="new_values.Run_Fees">
                    </div>
                    <label for="course-desc">Course Description</label>
                    <textarea class="form-control" rows="10" id="course-desc" placeholder="Course Description🖊️" v-model="new_values.Run_Desc"></textarea>

                    <!-- <div class="form-group">
                        <label for="course-name">Course Name</label>
                        <input type="text" class="form-control" id="course-name" placeholder="Course Name"  v-model="course.course_name">
                    </div>
                    <label for="course-desc">Course Description</label>
                    <textarea class="form-control" rows="10" id="course-desc" placeholder="Course Description🖊️"  v-model="course.course_desc"></textarea>
                    <br/> -->

                    <br/>
                    <button type="button" class="btn btn-primary" @click="updateAsInstructor()">Update Run</button> 
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from "axios"
    import Swal from 'sweetalert2'
    import router from '../../router'
    import {GETALLINSTRUCTORS_URL, GETRUNINSTRUCTOR_URL, ADMINUPDATERUN_URL, UPDATEASINTRUCTOR_URL} from "../../variables"
    export default{
        name: "IndividualCourse",
        data(){
            return{
                instructors:[],
                old_instructor:[],
                old_instructor_id: '',
                current_idx:'',
                date: '2018-05-23',
                new_values:{
                    // Run_StartDate: this.selectedCourse.Run_StartDate,
                    // Run_EndDate: this.selectedCourse.Run_EndDate,
                    // Run_StartTime: this.selectedCourse.Run_StartTime,
                    // Run_EndTime: this.selectedCourse.Run_EndTime,
                    // Run_Days: this.selectedCourse.Run_Days,
                    // Run_MinSlots: this.selectedCourse.Run_MinSlots,
                    // Run_MaxSlots: this.selectedCourse.Run_MaxSlots,
                    // Run_Venue: this.selectedCourse.Run_Venue,
                    // Run_Fees: this.selectedCourse.Run_Fees,
                    // Run_Desc: this.selectedCourse.Run_Desc,
                    ...this.selectedCourse
                },
                instructor_list: []
            }
        },

        props:{
            selectedCourse: Object
        },
        methods:{
            getAllInstructors(){
                axios.get(GETALLINSTRUCTORS_URL,
                {withCredentials: true}
                ).then(response => (
                    this.instructors = response.data,
                    this.current = this.instructors[0],
                    this.sortList()
                    
                ))
            },
            goBack(){
                this.$parent.back()
            },
            getRunInstructor(){
                axios.post(GETRUNINSTRUCTOR_URL,{
                    'course': this.new_values
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data.length != 0){
                        for(var i = 0; i < response.data.length; i++){
                                
                            for(var x = 0; x < this.instructors.length; x++){
                                if(response.data[i].User_ID === this.instructors[x].User_ID){
                                    this.instructors.splice(x, 1)
                                }
                            }
                        }
                        this.instructor_list = response.data
                    }
        
                })
            },
            update(){
                if (this.current_instructor == ''){
                    this.current_instructor = this.old_instructor_id
                }
                axios.post(ADMINUPDATERUN_URL,{
                    'instructors': this.instructor_list,
                    'course': this.new_values,
                    'old_course': this.selectedCourse
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data === 'no rights'){
                        this.redirectToCourse()
                    }else if (response.data === 'updated'){
                        this.updatedCourse()
                        this.goBack()
                    }
                    
                })
            },
            updateAsInstructor(){
                axios.post(UPDATEASINTRUCTOR_URL,{
                    'course': this.new_values,
                    'old_course': this.selectedCourse
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data === 'no rights'){
                        this.redirectToCourse()
                    }else if (response.data === 'updated'){
                        this.updatedCourse()
                        this.goBack()
                    }
                    
                })  
            },
            addInstructor(idx){
                this.instructor_list.push(this.instructors.splice(idx, 1)[0])
                document.getElementById('instructor').onclick = function(){
                    this.selectedIndex = 0;
                }
                
            },
            removeInstructor(idx){
                this.instructors.push(this.instructor_list.splice(idx, 1)[0])
                this.sortList()
            },
            sortList(){
                this.instructors.sort(function(a, b){
                    var nameA=a.User_Name.toLowerCase(), nameB=b.User_Name.toLowerCase()
                    if (nameA < nameB) //sort string ascending
                        return -1 
                    if (nameA > nameB)
                        return 1
                    return 0 //default return value (no sorting)
                })
            },
            redirectToCourse(){
                let timerInterval
                Swal.fire({
                icon: 'error',
                title: 'Access denied!',
                html: 'You do not have the access rights! Redirecting...',
                timer: 3000,
                timerProgressBar: true,
                didOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    clearInterval(timerInterval)
                }
                })
                router.push('/courses')
            },
            updatedCourse(){
                Swal.fire({
                    icon: 'success',
                    title: 'Course status has been changed!',
                })
            }
        },
        mounted(){
            this.getAllInstructors()
            this.getRunInstructor()
            this.new_values.Run_StartTime = this.selectedCourse.Run_StartTime.slice(0, -3)
            this.new_values.Run_EndTime = this.selectedCourse.Run_EndTime.slice(0, -3)
            // console.log('hi'.slice(0,-1))
        }
    }
</script>
<style>

    container-fluid > .back {
        display: inline-block;
    }

    container-fluid > .table-container {
        float:middle;
    }

    @media screen and (max-width: 1400px) {
        .table-container {
            width: 1400px;
        }
    }

    /* For Tablets */
    @media screen and (min-width: 540px) and (max-width: 780px) {
        .table-container {
            width: 900px;
        }
    }

    label{
        font-weight:bold;
        padding-right: 10px;
    }

    .row {
        margin-bottom: 10px;
    }
    /* input{
        margin-right: 30px;
    } */

    #course-id-alert{
        color:var(--pill-red);
    }
    /* .back button{
        background-color: var(--navigation-color);
        border: none;
        font-size: 2em;
        transition:0.2s;
    }
    .back button:hover{
        color: var(--button-color);
    }
    .back button:focus{
        outline:none;
        box-shadow:none;
    } */

    .back {
        border: 2px solid #fff;
        width: 40px;
        height: 40px;
        border-radius: 100%;
        position: relative;
    }
    #backbutton{
        width:100%; cursor: pointer; position: absolute;
    }
    #backbutton .arrow{left: 30%;}
    .arrow {position: absolute; bottom: 0;  margin-left:0px; width: 12px; height: 12px; background-size: contain; top:15px;}
    .segunda{margin-left: 8px;}
    .next {
        background-image: url(data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiI+PHN0eWxlPi5zdDB7ZmlsbDojZmZmfTwvc3R5bGU+PHBhdGggY2xhc3M9InN0MCIgZD0iTTMxOS4xIDIxN2MyMC4yIDIwLjIgMTkuOSA1My4yLS42IDczLjdzLTUzLjUgMjAuOC03My43LjZsLTE5MC0xOTBjLTIwLjEtMjAuMi0xOS44LTUzLjIuNy03My43UzEwOSA2LjggMTI5LjEgMjdsMTkwIDE5MHoiLz48cGF0aCBjbGFzcz0ic3QwIiBkPSJNMzE5LjEgMjkwLjVjMjAuMi0yMC4yIDE5LjktNTMuMi0uNi03My43cy01My41LTIwLjgtNzMuNy0uNmwtMTkwIDE5MGMtMjAuMiAyMC4yLTE5LjkgNTMuMi42IDczLjdzNTMuNSAyMC44IDczLjcuNmwxOTAtMTkweiIvPjwvc3ZnPg==);
    }

    @keyframes bounceAlpha {
        0% {opacity: 1; transform: translateX(0px) scale(1);}
        25%{opacity: 0; transform:translateX(10px) scale(0.9);}
        26%{opacity: 0; transform:translateX(-10px) scale(0.9);}
        55% {opacity: 1; transform: translateX(0px) scale(1);}
    }

    .bounceAlpha {
        animation-name: bounceAlpha;
        animation-duration:1.4s;
        animation-iteration-count:infinite;
        animation-timing-function:linear;
    }

    .arrow.primera.bounceAlpha {
        animation-name: bounceAlpha;
        animation-duration:1.4s;
        animation-delay:0.2s;
        animation-iteration-count:infinite;
        animation-timing-function:linear;
    }

    .back:hover .arrow{
        animation-name: bounceAlpha;
        animation-duration:1.4s;
        animation-iteration-count:infinite;
        animation-timing-function:linear;
    }
    .back:hover .arrow.primera{
        animation-name: bounceAlpha;
        animation-duration:1.4s;
        animation-delay:0.2s;
        animation-iteration-count:infinite;
        animation-timing-function:linear;
    }

    .selected-instructor{
        display:inline-block;
        background-color: var(--button-color);
        color: white;
        border-radius: 10px;
        padding: 5px;
        margin: 10px 5px 10px 0;
    }

</style>