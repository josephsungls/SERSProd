<template>
    <div class="container-fluid  display-container">
        <span class='back'>
            <button type='button' @click="goBack()"><i class="fas fa-long-arrow-alt-left"></i></button>
        </span>
        <div class="table-container">
            <div class="table-header">
                <h4><i class="fas fa-edit"></i> Edit Run Details Instructor</h4>
                <br/>
                <h5>{{selectedCourse.Course_ID}} - {{selectedCourse.Course_Name}}</h5>
                <form>
                    
                    <label for="instructor" >Instructor</label>
                    
                    <select class="form-control" id="instructor"  v-model="current_idx" @change="addInstructor(current_idx)" disabled>
                        <option id="default" value="" disabled selected>Select instructor(s) to assign them to the course</option>
                        <option id="selection" v-for="(instructor, idx) in instructors" :key="instructor" :value="idx">{{instructor.User_Email}}</option>
                    </select>
                    <span class="selected-instructor" v-for="(instructor, idx) in instructor_list" :key="idx">{{instructor.User_Email}} </span>
                    <br/>
                    <div class="form-group">
                        <label for="start">Start date:</label>
                        <input type="date" id="start"
                            v-model="new_values.Run_StartDate">

                        <label for="start">End date:</label>
                        <input type="date" id="end"
                            v-model="new_values.Run_EndDate"
                            :min="new_values.Run_StartDate">
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
                    <button type="button" class="btn btn-primary mr-2" @click="updateAsInstructor()">Update Run</button> 
                    <button type="button" class="btn btn-primary" @click="instructorReady()">Instructor Ready!</button> 
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import axios from "axios"
    import Swal from 'sweetalert2'
    import router from '../../router'
    import {GETALLINSTRUCTORS_URL, GETRUNINSTRUCTOR_URL, UPDATEASINTRUCTOR_URL, UPDATEREADYSTATUS_URL} from "../../variables"
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
                    ...this.selectedCourse
                },
                instructor_list: [],
                update_values: {...this.selectedCourse}
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
                axios.post(GETRUNINSTRUCTOR_URL,
                {'course':this.selectedCourse},
                {withCredentials: true},
                {
                    'course': this.new_values
                }).catch(function(error){
            
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
            // update(){
            //     if (this.current_instructor == ''){
            //         this.current_instructor = this.old_instructor_id
            //     }
            //     axios.post(ADMINUPDATERUN_URL,{
            //         'instructors': this.instructor_list,
            //         'course': this.new_values,
            //         'old_course': this.selectedCourse
            //     }).catch(function(error){
            
            //         console.log(error)
            //         return
            //     })
            //     .then(response=>{
            //         console.log(response.data)
            //         this.goBack()
            //     })
            // },
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
                        }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            router.push('/courses')
                        }
                        })
                    }
                    if (response.data === 'updated'){
                        this.update_values = {...this.new_values}
                        Swal.fire({
                        icon: 'success',
                        title: 'Course details updated',
                        showConfirmButton: false,
                        timer: 1500
                        })
                    }
                    
                })  
            },
            updateReadyStatus(){
                axios.post(UPDATEREADYSTATUS_URL,{
                    'course': this.update_values
                },
                {withCredentials: true}
                ).catch(function(error){
            
                    console.log(error)
                    return
                })
                .then(response=>{
                    if (response.data === 'no rights'){
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
                    }
                    if (response.data === 'updated'){
                        this.update_values = {...this.new_values}
                        Swal.fire({
                        icon: 'success',
                        title: 'Indicated',
                        text: 'The coordinator can now open the course up for registration ',
                        showConfirmButton: false,
                        timer: 1500
                        })
                        this.goBack()
                    }
                })    
            },
            instructorReady(){
                Swal.fire({
                title: 'Course is ready!',
                html: "By indicating that the course is ready, the coordinator will open the course up for registration. You won't be able to revert this action. <br/> <br/> <b>Note: If there are any updates, please ensure you clicked the 'Update Run' button before indicating your are ready!</b>",
                showCancelButton: true,
                confirmButtonColor: 'var(--pill-green)',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ready!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.updateReadyStatus()
                    }
                })
            },
            // addInstructor(idx){
            //     this.instructor_list.push(this.instructors.splice(idx, 1)[0])
            //     document.getElementById('instructor').onclick = function(){
            //         this.selectedIndex = 0;
            //     }
                
            // },
            // removeInstructor(idx){
            //     this.instructors.push(this.instructor_list.splice(idx, 1)[0])
            //     this.sortList()
            // },
            sortList(){
                this.instructors.sort(function(a, b){
                    var nameA=a.User_Name.toLowerCase(), nameB=b.User_Name.toLowerCase()
                    if (nameA < nameB) //sort string ascending
                        return -1 
                    if (nameA > nameB)
                        return 1
                    return 0 //default return value (no sorting)
                })
            }
        },
        mounted(){
            this.getAllInstructors()
            this.getRunInstructor()
            console.log(this.selectedCourse)
            this.new_values.Run_StartTime = this.selectedCourse.Run_StartTime.slice(0, -3)
            this.new_values.Run_EndTime = this.selectedCourse.Run_EndTime.slice(0, -3)
            // console.log('hi'.slice(0,-1))
        }
    }
</script>
<style>
    label{
        font-weight:bold;
    }
    #course-id-alert{
        color:var(--pill-red);
    }
    .back button{
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