document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('promoVideo');
    const playPauseBtn = document.querySelector('.play-pause-btn');
    const volumeBtn = document.querySelector('.volume-btn');
    const fullscreenBtn = document.querySelector('.fullscreen-btn');
    const progressBar = document.querySelector('.video-progress');
    const playIcon = playPauseBtn.querySelector('i');
    const volumeIcon = volumeBtn.querySelector('i');
    const fullscreenIcon = fullscreenBtn.querySelector('i');

    // تنظیم اولیه: صدا قطع و آیکون متناسب
    video.muted = true;
    volumeIcon.className = 'fas fa-volume-mute';

    // Play/Pause functionality
    playPauseBtn.addEventListener('click', function() {
        if (video.paused) {
            video.play();
            playIcon.className = 'fas fa-pause';
        } else {
            video.pause();
            playIcon.className = 'fas fa-play';
        }
    });

    // Volume control
    volumeBtn.addEventListener('click', function() {
        if (video.muted) {
            video.muted = false;
            volumeIcon.className = 'fas fa-volume-up';
        } else {
            video.muted = true;
            volumeIcon.className = 'fas fa-volume-mute';
        }
    });

    // Fullscreen functionality
    fullscreenBtn.addEventListener('click', function() {
        if (!document.fullscreenElement) {
            video.requestFullscreen().catch(err => {
                console.log(`Error attempting to enable fullscreen: ${err.message}`);
            });
            fullscreenIcon.className = 'fas fa-compress';
        } else {
            document.exitFullscreen();
            fullscreenIcon.className = 'fas fa-expand';
        }
    });

    // Update progress bar (راست به چپ برای فارسی)
    video.addEventListener('timeupdate', function() {
        const percent = (video.currentTime / video.duration) * 100;
        progressBar.value = percent;

        // تغییر جهت پیشرفت بار از چپ به راست
        progressBar.style.background = `linear-gradient(to right, #9a563a 0%, #9a563a ${percent}%, rgba(255,255,255,0.3) ${percent}%, rgba(255,255,255,0.3) 100%)`;
    });

    // Seek functionality
    progressBar.addEventListener('input', function() {
        const seekTime = (progressBar.value / 100) * video.duration;
        video.currentTime = seekTime;

        // به روزرسانی رنگ نوار پیشرفت هنگام تغییر دستی
        progressBar.style.background = `linear-gradient(to right, #9a563a 0%, #9a563a ${this.value}%, rgba(255,255,255,0.3) ${this.value}%, rgba(255,255,255,0.3) 100%)`;
    });

    // Auto-play when in view
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                video.play().catch(function(error) {
                    console.log("Auto-play prevented:", error);
                });
                playIcon.className = 'fas fa-pause';
            } else {
                video.pause();
                playIcon.className = 'fas fa-play';
            }
        });
    }, {
        threshold: 0.5
    });

    observer.observe(video);
});







// Global state for calendar       تقویم تاریخ
window.PsCalendarState = {
    targetInput: null,
    callback: null
};

// Jalali conversion utils
(function(global){
    const breaks=[-61,9,38,199,426,686,756,818,1111,1181,1210,1635,2060,2097,2192,2262,2324,2394,2456,3178];
    function div(a,b){return ~~(a/b);} 
    function g2d(gy,gm,gd){let a=div(14-gm,12);let y=gy+4800-a;let m=gm+12*a-3;return gd+div((153*m+2),5)+365*y+div(y,4)-div(y,100)+div(y,400)-32045;}
    function d2g(jdn){let a=jdn+32044;let b=div((4*a+3),146097);let c=a-div(146097*b,4);let d=div((4*c+3),1461);let e=c-div(1461*d,4);let m=div((5*e+2),153);let day=e-div((153*m+2),5)+1;let month=m+3-12*div(m,10);let year=100*b+d-4800+div(m,10);return {gy:year,gm:month,gd:day};}
    function jalCal(jy){let bl=breaks.length,gy=jy+621;let leapJ=-14,jp=breaks[0],jm,jump,n,i;if(jy<jp||jy>=breaks[bl-1])throw new Error("سال جلالی خارج از بازه است");for(i=1;i<bl;i++){jm=breaks[i];jump=jm-jp;if(jy<jm)break;leapJ=leapJ+div(jump,33)*8+div((jump%33),4);jp=jm;}n=jy-jp;leapJ=leapJ+div(n,33)*8+div((n%33+3),4);let leapG=div(gy,4)-div((div(gy,100)+1)*3,4)-150;let march=20+leapJ-leapG;if(jump-n===4&&jump%33===4)march++;return {gy,march,leap:((n+1)%33-1)%4};}
    function j2d(jy,jm,jd){let r=jalCal(jy);return g2d(r.gy,3,r.march)+(jm-1)*31-div(jm,7)*(jm-7)+jd-1;}
    function d2j(jdn){let g=d2g(jdn),jy=g.gy-621;let r=jalCal(jy),jdn1f=g2d(r.gy,3,r.march);let jd,jm,k=jdn-jdn1f;if(k>=0){if(k<=185){jm=1+div(k,31);jd=(k%31)+1;return {jy,jm,jd};}else{k-=186;jm=7+div(k,30);jd=(k%30)+1;return {jy,jm,jd};}}else{jy-=1;r=jalCal(jy);jdn1f=g2d(r.gy,3,r.march);k=jdn-jdn1f;if(k<=185){jm=1+div(k,31);jd=(k%31)+1;}else{k-=186;jm=7+div(k,30);jd=(k%30)+1;}return {jy,jm,jd};}}
    function toGregorian(jy,jm,jd){return d2g(j2d(jy,jm,jd));}
    function toJalaali(gy,gm,gd){return d2j(g2d(gy,gm,gd));}
    function isLeapJalaaliYear(jy){return jalCal(jy).leap===0;}
    function jalaaliMonthLength(jy,jm){if(jm<=6)return 31;if(jm<=11)return 30;return isLeapJalaaliYear(jy)?30:29;}
    global.Jalali={toGregorian,toJalaali,jalaaliMonthLength,toJdn:j2d};
})(window);

// Persian Calendar Implementation
(function(){
    const monthNames=["فروردین","اردیبهشت","خرداد","تیر","مرداد","شهریور","مهر","آبان","آذر","دی","بهمن","اسفند"];
    const daysBody=document.getElementById('psDaysBody');
    const monthLabel=document.getElementById('psMonth');
    const yearInput=document.getElementById('psYear');
    const prevBtn=document.getElementById('psPrev');
    const nextBtn=document.getElementById('psNext');
    const todayLabel=document.getElementById('psToday');
    const selectedLabel=document.getElementById('psSelected');
    const backdrop=document.getElementById('psCalendarBackdrop');

    const now=new Date();
    const jNow=Jalali.toJalaali(now.getFullYear(),now.getMonth()+1,now.getDate());

    function jsWeekdayOfJalaliFirst(jy,jm){const g=Jalali.toGregorian(jy,jm,1);const d=new Date(g.gy,g.gm-1,g.gd).getDay();return (d+1)%7;}

    const SOLAR_HOLIDAYS=[{m:1,d:1,name:"عید نوروز"},{m:1,d:2,name:"عید نوروز"},{m:1,d:3,name:"عید نوروز"},{m:1,d:4,name:"عید نوروز"},{m:1,d:12,name:"روز جمهوری اسلامی"},{m:1,d:13,name:"سیزده‌بدر"},{m:3,d:14,name:"رحلت امام خمینی"},{m:3,d:15,name:"قیام ۱۵ خرداد"},{m:11,d:22,name:"پیروزی انقلاب"},{m:12,d:29,name:"ملی شدن نفت"}];
    function getHolidayInfo(jy,jm,jd){return SOLAR_HOLIDAYS.find(h=>h.m===jm&&h.d===jd)||null;}

    let state={jy:jNow.jy,jm:jNow.jm,selected:null};

    function render(){
        yearInput.value=state.jy;
        monthLabel.textContent=monthNames[state.jm-1];
        todayLabel.textContent=`${jNow.jy}/${String(jNow.jm).padStart(2,'0')}/${String(jNow.jd).padStart(2,'0')}`;
        daysBody.innerHTML='';
        
        const firstIdx=jsWeekdayOfJalaliFirst(state.jy,state.jm);
        const dim=Jalali.jalaaliMonthLength(state.jy,state.jm);
        let row=document.createElement('tr');
        
        for(let i=0;i<firstIdx;i++){
            const td=document.createElement('td');
            td.innerHTML=`<span class="ps-day disabled"></span>`;
            row.appendChild(td);
        } 
        
        for(let d=1;d<=dim;d++){
            if(row.children.length===7){
                daysBody.appendChild(row);
                row=document.createElement('tr');
            }
            const td=document.createElement('td');
            const btn=document.createElement('div');
            btn.className='ps-day';
            btn.textContent=d;
            btn.setAttribute('data-date', `${state.jy}/${String(state.jm).padStart(2,'0')}/${String(d).padStart(2,'0')}`);
            
            const hinfo=getHolidayInfo(state.jy,state.jm,d);
            if(hinfo){
                btn.classList.add('holiday');
                btn.title=hinfo.name;
            }
            
            const weekday=(firstIdx + d - 1) % 7;
            if(weekday === 6) btn.classList.add('friday');

            if(state.jy===jNow.jy && state.jm===jNow.jm && d===jNow.jd){
                btn.classList.add('today');
                const dot=document.createElement('span');
                dot.className='ps-dot-today';
                btn.appendChild(dot);
            }

            if(state.selected && state.selected.jy===state.jy && state.selected.jm===state.jm && state.selected.jd===d){
                btn.classList.add('selected');
            }

            btn.addEventListener('click',()=>{
                state.selected={jy:state.jy,jm:state.jm,jd:d};
                const selectedDateFormatted = `${state.jy}/${String(state.jm).padStart(2,'0')}/${String(d).padStart(2,'0')}`;
                selectedLabel.textContent=selectedDateFormatted + (hinfo ? ` (${hinfo.name})` : '');
                
                // Call the callback function if set
                if(window.PsCalendarState.callback){
                    window.PsCalendarState.callback(selectedDateFormatted);
                }
                
                hideBackdrop();
                render();
            });
            
            td.appendChild(btn);
            row.appendChild(td);
        }
        
        if(row.children.length) daysBody.appendChild(row);
        if(!state.selected) selectedLabel.textContent='—';
    }

    prevBtn.addEventListener('click',()=>{
        state.jm--;
        if(state.jm<1){
            state.jm=12;
            state.jy--;
        }
        render();
    });
    
    nextBtn.addEventListener('click',()=>{
        state.jm++;
        if(state.jm>12){
            state.jm=1;
            state.jy++;
        }
        render();
    });
    
    yearInput.addEventListener('change',(e)=>{
        const v=parseInt(e.target.value,10);
        if(!isNaN(v)&&v>=+yearInput.min&&v<=+yearInput.max){
            state.jy=v;
            render();
        }else{
            e.target.value=state.jy;
        }
    });

    function showBackdrop(){
        backdrop.classList.add('show');
        backdrop.setAttribute('aria-hidden','false');
    }
    
    function hideBackdrop(){
        backdrop.classList.remove('show');
        backdrop.setAttribute('aria-hidden','true');
    }

    // Close when clicking outside modal
    backdrop.addEventListener('click',(e)=>{
        if(e.target===backdrop) hideBackdrop();
    });

    // Expose functions globally
    window.PsCalendar = {
        show: function(targetInput, callback) {
            window.PsCalendarState.targetInput = targetInput;
            window.PsCalendarState.callback = callback;
            showBackdrop();
        },
        hide: hideBackdrop
    };

    // Initialize
    render();
})();

// Calendar event handlers
document.addEventListener('DOMContentLoaded', function() {
    // Event for bookingDate input and icon in hero section
    var bookingDateInput = document.getElementById('bookingDate');
    var bookingDateIcon = document.getElementById('bookingDateOpen');
    
    if (bookingDateInput && bookingDateIcon) {
        function openPersianCalendarForHero() {
            window.PsCalendar.show(bookingDateInput, function(selectedDate) {
                bookingDateInput.value = selectedDate;
            });
        }
        
        bookingDateInput.addEventListener('click', openPersianCalendarForHero);
        bookingDateIcon.addEventListener('click', openPersianCalendarForHero);
    }

    // Event for bookingDateForm input and icon in reservation form area
    var bookingDateFormInput = document.getElementById('bookingDateForm');
    var bookingDateFormIcon = document.getElementById('bookingDateFormOpen');
    
    if (bookingDateFormInput && bookingDateFormIcon) {
        function openPersianCalendarForForm() {
            window.PsCalendar.show(bookingDateFormInput, function(selectedDate) {
                bookingDateFormInput.value = selectedDate;
            });
        }
        
        bookingDateFormInput.addEventListener('click', openPersianCalendarForForm);
        bookingDateFormIcon.addEventListener('click', openPersianCalendarForForm);
    }

        // Event for bookingDateStart input and icon in reservation form area
    var bookingDateStartInput = document.getElementById('bookingDateStart');
    var bookingDateStartIcon = document.getElementById('bookingDateStartOpen');
    
    if (bookingDateStartInput && bookingDateStartIcon) {
        function openPersianCalendarForForm() {
            window.PsCalendar.show(bookingDateStartInput, function(selectedDate) {
                bookingDateStartInput.value = selectedDate;
            });
        }
        
        bookingDateStartInput.addEventListener('click', openPersianCalendarForForm);
        bookingDateStartIcon.addEventListener('click', openPersianCalendarForForm);
    }
});

// Global state for calendar       تقویم تاریخ تمام

