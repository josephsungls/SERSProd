<template>
    <div class = "container-fluid display-container">
        <div class="table-container">
            <div class="table-header">
                <h4>All courses</h4>
                <p class = "text-muted">Ongoing campaign and registration runs</p>
            </div>
            <table class = "table">
                <thead>
                    <tr>
                        <th scope = "col">Course Code & Name</th>
                        <th scope = "col">Instructor(s)</th>
                        <th scope = "col">Date</th>
                        <th scope = "col">Slots Taken/<br/>Interest Count</th>
                        <th scope = "col">Deadline</th>
                        
                        <!-- <th scope = "col" class = "status text-center">Course Status</th> -->
                    </tr>
                </thead>
                <tbody v-if="allCourses.length > 0">
                    <tr v-for="course in allCourses" :key="course">
                        <!-- {{course}} -->
                        <td>
                            <b>
                                {{course.Course_ID}} 
                            </b> 
                            <br> 
                            {{course.Course_Name}} <span v-if="course.Class">- Class:{{course.Class}}</span>
                        </td>
                        <td v-if="course.User_Name"><br> {{processInstructor(course.User_Name)}}</td>
                        <td v-else> - </td>
                        <td>
                            {{course.Run_StartDate}}
                            {{course.Vote_Start}}
                            <br> 
                            <small class="text-muted">{{course.Run_StartTime}}</small>
                        </td>
                        <td v-if="course.Run_MaxSlots">{{course.No_Registrations}}/{{course.Run_MaxSlots}}</td>
                        <td v-else|>{{course.No_Interest}}</td>
                        <td>
                            {{course.End_Date}}
                        </td>
                        
                       
                    </tr>
                </tbody>
            </table>


        </div>
    </div>
</template>
<script>
    import axios from 'axios'
    import {ALLCOURSESREGISTRATION_URL, ALLCOURSESFILTEREDCOURSE_URL} from '../../../variables'
    export default{
        name:'YourClasses',
        data(){
            return{
                allCourses: []
            }
        },
        methods: {
            getRegistrations(){
                // console.log(REGISTRATIONS_URL)
                axios.get(ALLCOURSESREGISTRATION_URL,{
                }
                ).then(response => {    
                    console.log('registration', response.data)                
                    this.allCourses.push(...response.data)
                })
            },
            getFilteredCourse(){
                // console.log(REGISTRATIONS_URL)
                axios.get(ALLCOURSESFILTEREDCOURSE_URL,{
                }
                ).then(response => {
                    console.log('filtered', response.data)                
                    this.allCourses.push(...response.data)
                    
                })
            },
            processInstructor(instructors){
                return instructors.split(',').join(', ')
            }
        },
        mounted(){
            this.getRegistrations()
            this.getFilteredCourse()
        }

    }
</script>
<style scoped>

</style>