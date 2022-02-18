<template>
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="accordian">
                <div class="accordian-item" @click = "test">
                    <div class="accordian-item-header">
                        <h4>How do I find my Windows Product key?</h4>
                    </div>
                    <div class="accordian-item-body">
                        <div class="accordian-item-body-content">
                            <p>
                            The product key is located inside the product packaging, on the
                            receipt or confirmation page for a digital purchase or in a
                            confirmation e-mail that shows you purchased Windows. If you
                            purchased a digital copy from Microsoft Store, you can locate your
                            product key in your Account under Digital Content.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="accordian-item">
                    <div class="accordian-item-header">
                        <h4>I've downloaded an ISO file, now what?</h4>
                    </div>
                    <div class="accordian-item-body">
                        <div class="accordian-item-body-content">
                            <p>
                            You can use the ISO file to create bootable media for installation
                            or recovery. You can also install Windows on your current device
                            by opening the ISO file, selecting the Setup and following the
                            instructions.
                            </p>
                            <p>
                            To create bootable media such as a bootable USB drive or DVD, you
                            will need an ISO burning or mounting software. We recommend always
                            using a blank USB or blank DVD because contents may be deleted
                            when creating a bootable image.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="accordian-item">
                    <div class="accordian-item-header">
                        <h4>
                        What's the difference between 32-bit and 64-bit versions of Windows?
                        </h4>
                    </div>
                    <div class="accordian-item-body">
                        <div class="accordian-item-body-content">
                            <p>
                            The terms 32-bit and 64-bit refer to the way a computer's
                            processor (also called a CPU) handles information. The 64-bit
                            version of Windows handles large amounts of random access memory
                            (RAM) more effectively than a 32-bit system. Not all devices can
                            run the 64-bit versions of Windows.
                            </p>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
	export default {
		name: "Faq",
        data(){
            return{
                accordianItemHeaders: null,
            }
        },
        mounted(){
            this.accordianItemHeaders = document.querySelectorAll('.accordian-item-header')
            this.accordianItemHeaders.forEach(accordianItemHeader => {
            accordianItemHeader.addEventListener("click", () => {
                const current = document.querySelector(".accordian-item-header.active");

                if (current && current !== accordianItemHeader) {
                current.classList.toggle("active");
                current.nextElementSibling.style.maxHeight = 0;
                }
                accordianItemHeader.classList.toggle("active");

                const accordianItemBody = accordianItemHeader.nextElementSibling;

                if (accordianItemHeader.classList.contains("active")) {
                accordianItemBody.style.maxHeight = accordianItemBody.scrollHeight + "px";
                } else {
                accordianItemBody.style.maxHeight = 0;
                }
            });
            });
            
        },
        methods:{
        }
	};
</script>
<style scoped>

.accordian {
  width: 90%;
  max-width: 1000px;
  margin: 2rem auto;
}

.accordian-item {
  background-color: white;
  color: #111;
  margin: 1rem 0;
  border-radius: 0.5rem;
  box-shadow: 0 2px 5px 0 rgba(0, 0, 0, 0.25);
}



.accordian-item-header {
  padding: 0.5rem 3rem 0.5rem 1rem;
  min-height: 3.5rem;
  line-height: 1.25rem;
  display: flex;
  align-items: center;
  position: relative;
  cursor: pointer;
  border-radius: 0.5rem;
  transition: 0.2s;
}

.accordian-item-header:hover{
    background-color: var(--button-color);
    color: white;
}

.accordian-item-header.active{
    border-top-left-radius: 0.5rem;
    border-top-right-radius: 0.5rem;
    border-bottom-left-radius: 0;
    border-bottom-right-radius: 0;
    background-color: var(--button-color);
    color: white;
}

.accordian-item-header::after {
  content: "\002B";
  font-size: 2rem;
  font-weight: bold;
  position: absolute;
  right: 1rem;
}

.accordian-item-header.active::after {
  content: "\2212";
}

.accordian-item-body {
  max-height: 0;
  overflow: hidden;
  transition: max-height 0.2s ease-out;
}

.accordian-item-body-content {
  padding: 1rem;
  line-height: 1.5;
  border-top: 2px solid var(--light-gray);
}

</style>