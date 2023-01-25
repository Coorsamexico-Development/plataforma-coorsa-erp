<script setup>
import { object } from '@amcharts/amcharts5';
import { ref } from 'vue';
/*Upload img functions*/ 
const emit = defineEmits(['retornar'])

class UploadModal 
{
    filename = "";
    isCopying = false;
    isUploading = false;
    progress = 0;
    progressTimeout = null;
    state = 0;

    constructor(el)
    {
        this.el = document.querySelector(el);
        this.el?.addEventListener("click",this.action.bind(this));
        this.el?.querySelector("#file")?.addEventListener("change",this.fileHandle.bind(this));
    }
     action(e) {
        this[e.target?.getAttribute("data-action")]?.();
        this.stateDisplay();
    }
    cancel() {
        this.isUploading = false;
        this.progress = 0;
        this.progressTimeout = null;
        this.state = 0;
        this.stateDisplay();
        this.progressDisplay();
        this.fileReset();
    }
    async copy() {
        const copyButton = this.el?.querySelector("[data-action='copy']");

        if (!this.isCopying && copyButton) {
            // disable
            this.isCopying = true;
            copyButton.style.width = `${copyButton.offsetWidth}px`;
            copyButton.disabled = true;
            copyButton.textContent = "Copied!";
            navigator.clipboard.writeText(this.filename);
            await new Promise(res => setTimeout(res, 1000));
            // reenable
            this.isCopying = false;
            copyButton.removeAttribute("style");
            copyButton.disabled = false;
            copyButton.textContent = "Copy Link";
        }
    }
    fail() {
        this.isUploading = false;
        this.progress = 0;
        this.progressTimeout = null;
        this.state = 2;
        this.stateDisplay();
    }
    file() {
        this.el?.querySelector("#file").click();
    }
    fileDisplay(name = "") {
        // update the name
        this.filename = name;

        const fileValue = this.el?.querySelector("[data-file]");
        if (fileValue) fileValue.textContent = this.filename;

        // show the file
        this.el?.setAttribute("data-ready", this.filename ? "true" : "false");
    }
    fileHandle(e) {
        return new Promise(() => {
            const { target } = e;
            if (target?.files.length) {
                let reader = new FileReader();
                reader.onload = e2 => {
                    this.fileDisplay(target.files[0].name);
                };
                reader.readAsDataURL(target.files[0]);
            }
        });
    }
    fileReset() {
        const fileField = this.el?.querySelector("#file");
        if (fileField) fileField.value = null;

        this.fileDisplay();
    }
    progressDisplay() {
        const progressValue = this.el?.querySelector("[data-progress-value]");
        const progressFill = this.el?.querySelector("[data-progress-fill]");
        const progressTimes100 = Math.floor(this.progress * 100);

        if (progressValue) progressValue.textContent = `${progressTimes100}%`;
        if (progressFill) progressFill.style.transform = `translateX(${progressTimes100}%)`;
    }
    async progressLoop() {
        this.progressDisplay();

        if (this.isUploading) {
            if (this.progress === 0) {
                await new Promise(res => setTimeout(res, 1000));
                // fail randomly
                if (!this.isUploading) 
                {
                    return;
                } else if (fileUpload.value == null) 
                {
                    this.fail();
                    return;
                }
            }
            // …or continue with progress
            if (this.progress < 1) {
                this.progress += 0.01;
                this.progressTimeout = setTimeout(this.progressLoop.bind(this), 50);
            } else if (this.progress >= 1) {
                this.progressTimeout = setTimeout(() => {
                    if (this.isUploading) {
                        this.success();
                        this.stateDisplay();
                        this.progressTimeout = null;
                    }
                }, 250);
            }
        }
    }
    stateDisplay() {
        this.el?.setAttribute("data-state", `${this.state}`);
    }
    success() {
        this.isUploading = false;
        this.state = 3;
        this.stateDisplay();
    }
    upload() {
        if (!this.isUploading) {
            this.isUploading = true;
            this.progress = 0;
            this.state = 1;
            this.progressLoop();
        }
    }

}


class Utils {
    static randomInt(min = 0,max = 2**32) {
        const percent = crypto.getRandomValues(new Uint32Array(1))[0] / 2**32;
        const relativeValue = (max - min) * percent;

        return Math.round(min + relativeValue);
    }
}


const fileUpload = ref(null);


const uploadFile = () =>
{
      new UploadModal("#upload");
}

const recuperarArchivo = () =>
{
    const file = fileUpload.value.files[0];
    //console.log(file)
    emit('retornar', file);
}

</script>
<template>
   <div id="upload" class="modal" data-state="0" data-ready="false">
        <div class="modal__body">
            <div class="modal__col">
                <!-- up -->
            	<svg class="modal__icon modal__icon--blue" viewBox="0 0 24 24" width="24px" height="24px" aria-hidden="true">
            		<g fill="none" stroke="hsl(223,90%,50%)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            			<circle class="modal__icon-sdo69" cx="12" cy="12" r="11" stroke-dasharray="69.12 69.12" />
            			<polyline class="modal__icon-sdo14" points="7 12 12 7 17 12" stroke-dasharray="14.2 14.2" />
            			<line class="modal__icon-sdo10" x1="12" y1="7" x2="12" y2="17" stroke-dasharray="10 10" />
            		</g>
            	</svg>
            	<!-- error -->
            	<svg class="modal__icon modal__icon--red" viewBox="0 0 24 24" width="24px" height="24px" aria-hidden="true" display="none">
            		<g fill="none" stroke="hsl(3,90%,50%)" stroke-width="2" stroke-linecap="round">
            			<circle class="modal__icon-sdo69" cx="12" cy="12" r="11" stroke-dasharray="69.12 69.12" />
            			<line class="modal__icon-sdo14" x1="7" y1="7" x2="17" y2="17" stroke-dasharray="14.2 14.2" />
            			<line class="modal__icon-sdo14" x1="17" y1="7" x2="7" y2="17" stroke-dasharray="14.2 14.2" />
            		</g>
            	</svg>
            	<!-- check -->
            	<svg class="modal__icon modal__icon--green" viewBox="0 0 24 24" width="24px" height="24px" aria-hidden="true" display="none">
            		<g fill="none" stroke="hsl(138,90%,50%)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            			<circle class="modal__icon-sdo69" cx="12" cy="12" r="11" stroke-dasharray="69.12 69.12" />
            			<polyline class="modal__icon-sdo14" points="7 12.5 10 15.5 17 8.5" stroke-dasharray="14.2 14.2" />
            		</g>
            	</svg>
            </div>
            <div class="modal__col">
            	<div class="modal__content">
                    
                    <h2 class="mt-2 modal__title">Subir archivo</h2>
            		<p class="modal__message">Selecciona el archivo que deseas subir.</p>
                
            		<div class="modal__actions">
            			<input @click="uploadFile();" @change="recuperarArchivo(); "  ref="fileUpload" class="modal__button modal__button--upload"  id="file" type="file" > <!--class="modal__button modal__button--upload" ref="fileUpload" @change="uploadFile"-->
            		</div>
            		<div class="modal__actions" hidden>
            			<svg class="modal__file-icon" viewBox="0 0 24 24" width="24px" height="24px" aria-hidden="true">
            				<g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            					<polygon points="4 1 12 1 20 8 20 23 4 23" />
            					<polyline points="12 1 12 8 20 8" />
            				</g>
            			</svg>
            			<div class="modal__file" data-file></div>
            			<button class="modal__close-button" type="button" data-action="fileReset">
            				<svg class="modal__close-icon" viewBox="0 0 16 16" width="16px" height="16px" aria-hidden="true">
            					<g fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            						<polyline points="4,4 12,12" />
            						<polyline points="12,4 4,12" />
            					</g>
            				</svg>
            				<span class="modal__sr">Quitar</span>
            			</button>
            			<button class="modal__button" type="button" data-action="upload">Subir</button>
            		</div>
            	</div>
            	<div class="modal__content" hidden>
            		<h2 class="modal__title">Subiendo</h2>
            		<p class="modal__message">Espere un momento, se esta procesando su archivo.</p>
            		<div class="modal__actions">
            			<div class="modal__progress">
            				<div class="modal__progress-value" data-progress-value>0%</div>
            				<div class="modal__progress-bar">
            					<div class="modal__progress-fill" data-progress-fill></div>
            				</div>
            			</div>
            		</div>
            	</div>
            	<div class="modal__content" hidden>
            		<h2 class="modal__title">Oops!</h2>
            		<p class="modal__message">Hubo un error en la subida de archivo. Intenta de nuevo.</p>
            		<div class="modal__actions modal__actions--center">
            			<button class="modal__button" type="button" data-action="upload">Reintentar</button>
            			<button class="modal__button" type="button" data-action="cancel">Cancelar</button>
            		</div>
            	</div>
            	<div class="modal__content" hidden>
            		<h2 class="modal__title">Subida existosa</h2>
            		<p class="modal__message">Tu archivo ha sido subido</p>
            		<div class="modal__actions modal__actions--center">
            			<!--<button class="modal__button" type="button" data-action="cancel">Done</button>-->
            		</div>
            	</div>
            </div>
        </div>
    </div>
</template>