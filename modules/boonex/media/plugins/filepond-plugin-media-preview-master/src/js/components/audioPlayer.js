'use strict';

export default class AudioPlayer {

    constructor(mediaEl, audioElems) {
        this.mediaEl = mediaEl;
        this.audioElems = audioElems;
        this.onplayhead = false;
        this.duration = 0;
        this.timelineWidth = this.audioElems.timeline.offsetWidth - this.audioElems.playhead.offsetWidth;
        this.moveplayheadFn = this.moveplayhead.bind(this);

        this.registerListeners();
    }

    registerListeners() {
        this.mediaEl.addEventListener("timeupdate", this.timeUpdate.bind(this), false);
        this.mediaEl.addEventListener("canplaythrough", () => this.duration = this.mediaEl.duration, false);
        this.audioElems.timeline.addEventListener("click", this.timelineClicked.bind(this), false);
        this.audioElems.button.addEventListener("click", this.play.bind(this))
        this.audioElems.playhead.addEventListener('mousedown', this.mouseDown.bind(this), false);
        window.addEventListener('mouseup', this.mouseUp.bind(this), false);
    }

    play() {
        if (this.mediaEl.paused) {
            this.mediaEl.play();
        } else {
            this.mediaEl.pause();
        }
        this.audioElems.button.classList.toggle('play');
        this.audioElems.button.classList.toggle('pause');
    }

    timeUpdate() {
        let playPercent = (this.mediaEl.currentTime / this.duration) * 100;
        this.audioElems.playhead.style.marginLeft = playPercent + "%";
        if (this.mediaEl.currentTime === this.duration) {
            this.audioElems.button.classList.toggle('play');
            this.audioElems.button.classList.toggle('pause');
        }
    }
    
    moveplayhead(event) {
        let newMargLeft = event.clientX - this.getPosition(this.audioElems.timeline);
        if (newMargLeft >= 0 && newMargLeft <= this.timelineWidth) {
            this.audioElems.playhead.style.marginLeft = newMargLeft + "px";
        }
        if (newMargLeft < 0) {
            this.audioElems.playhead.style.marginLeft = "0px";
        }
        if (newMargLeft > this.timelineWidth) {
            this.audioElems.playhead.style.marginLeft = this.timelineWidth - 4 + "px";
        }
    }

    timelineClicked(event) {
        this.moveplayhead(event);
        this.mediaEl.currentTime = this.duration * this.clickPercent(event);
    }

    mouseDown() {
        this.onplayhead = true;
        window.addEventListener('mousemove', this.moveplayheadFn,true);
        this.mediaEl.removeEventListener('timeupdate', this.timeUpdate.bind(this), false);
    }

    mouseUp(event) {
        window.removeEventListener('mousemove', this.moveplayheadFn, true);
        if (this.onplayhead == true) {
            this.moveplayhead(event);
            // change current time
            this.mediaEl.currentTime = this.duration * this.clickPercent(event);
            this.mediaEl.addEventListener('timeupdate', this.timeUpdate.bind(this), false);
        }
        this.onplayhead = false;
    }

    clickPercent(event) {
        return (event.clientX - this.getPosition(this.audioElems.timeline)) / this.timelineWidth;
    }

    getPosition(el) {
        return el.getBoundingClientRect().left;
    }

}