<template>
    <div class = "container-fluid display-container">

        <div class="table-container">
            <div class="table-header">
                <h4>Attended Runs</h4>
                <p class = "text-muted">Courses which you have attended.</p>
            </div>
        
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Name</th>
                        <th scope = "col">Class Date</th>
                        <th scope = "col">Registration Status</th>
                        <!-- <th scope = "col">Completion Status</th> -->
                    </tr>
                </thead>
                <tbody  v-if="filtered_courses.length != 0" >
                    
                    <tr v-for="course in filtered_courses" :key="course">
                        <td><b>{{course.Course_Name}} - {{course.Class}}</b> <br> {{course.Run_Desc}}</td>
                        <td><p><b>{{course.Run_StartDate}}</b> <br/> {{course.Run_StartTime}}</p></td>
                        <td>
                            <span :class="['badge', 'badge-pill' , 'completed' ]">

                                <span>Attended</span>

                            </span>
                        </td>
                        <!-- <td>
                            <span :class="['badge', 'badge-pill' , course.Completion_Status]">

                                <span>{{course.Completion_Status}}</span>

                            </span>
                        </td> -->
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr colspan="3">
                        <div class="table-header"><h4>No courses Attended</h4></div>
                    </tr>
                </tbody>
            </table>
        </div>
        

    </div>
</template>
<script>
// INDICATEDINTEREST_URL
// ONGOINGCAMPAIGN_URL
    import axios from 'axios'
    import {ATTENDEDCOURSES_URL} from '../../../variables'
    export default {
        name: "Registered",
        data(){
            return {
                filtered_courses: [],
                indicated: {},
                current: {}
            }
        },
        methods:{
           getRegisteredCourse(){
                    axios.get(ATTENDEDCOURSES_URL,
                    {
                        withCredentials: true
                    }
                    ).catch(function(error){
                        console.log(error)
                        return
                    })
                    .then(response=>{
                        console.log(response.data)
                        this.filtered_courses = response.data
                        
                    })

            },
            changeStatus(course){
                this.current = course
            },
        },
        mounted(){
            this.getRegisteredCourse()
        }
    }
</script>
<style scoped>

    .badge-pill{
        width: 100px;
    }
    :deep(.ongoing){
        background-color: var(--pill-yellow);
        color: white;
    }
    :deep(.offered){
        background-color: var(--pill-green);
        color: white;
    }
    :deep(.closed){
        background-color: var(--pill-red);
        color: white;
    }
    :deep(.pending),
    :deep(.not.completed),
    :deep(.dropped){
        background-color: var(--gray);
        color: white;
    }
    :deep(.completed){
        background-color: var(--pill-green);
        color: white;
    }
</style>