/**
 * Date/Time Picker Helper for Contest Forms
 * Adds convenient buttons to quickly set dates relative to current time
 */

(function() {
    'use strict';

    // Configuration
    const TIMEZONE_FORMAT = {
        timeZoneName: 'short'
    };

    const TIME_INCREMENTS = {
        days: [1, 2, 3, 5, 7, 14, 30],
        hours: [1, 2, 3, 5, 8, 12, 24],
        minutes: [5, 10, 15, 30, 45, 60]
    };

    const FIELD_IDS = {
        activateTime: 'contest_activatetimeString',
        startTime: 'contest_starttimeString',
        freezeTime: 'contest_freezetimeString',
        endTime: 'contest_endtimeString',
        unfreezeTime: 'contest_unfreezetimeString',
        deactivateTime: 'contest_deactivatetimeString'
    };

    // Fields that must always be absolute (no format toggle)
    const ABSOLUTE_ONLY_FIELDS = [FIELD_IDS.activateTime, FIELD_IDS.startTime];

    /**
     * Format a Date object to DOMjudge timestamp format
     */
    function formatTimestamp(date, timezone = null, useLocalTime = false) {
        let year, month, day, hours, minutes, seconds;
        
        if (useLocalTime) {
            year = date.getFullYear();
            month = String(date.getMonth() + 1).padStart(2, '0');
            day = String(date.getDate()).padStart(2, '0');
            hours = String(date.getHours()).padStart(2, '0');
            minutes = String(date.getMinutes()).padStart(2, '0');
            seconds = String(date.getSeconds()).padStart(2, '0');
        } else {
            // Use UTC methods to avoid timezone conversions
            year = date.getUTCFullYear();
            month = String(date.getUTCMonth() + 1).padStart(2, '0');
            day = String(date.getUTCDate()).padStart(2, '0');
            hours = String(date.getUTCHours()).padStart(2, '0');
            minutes = String(date.getUTCMinutes()).padStart(2, '0');
            seconds = String(date.getUTCSeconds()).padStart(2, '0');
        }
        
        if (!timezone) {
            timezone = Intl.DateTimeFormat('en-US', TIMEZONE_FORMAT)
                .formatToParts(new Date())
                .find(part => part.type === 'timeZoneName').value;
        }
        
        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds} ${timezone}`;
    }

    /**
     * Parse a DOMjudge timestamp to components
     */
    function parseTimestamp(timestamp) {
        if (!timestamp) return null;
        
        const match = timestamp.match(/^(\d{4})-(\d{2})-(\d{2})\s+(\d{2}):(\d{2}):(\d{2})(?:\.(\d+))?\s+(.+)$/);
        if (!match) return null;
        
        const [_, year, month, day, hours, minutes, seconds, , timezone] = match;
        
        // Create date in UTC to avoid local timezone issues
        const date = new Date(Date.UTC(
            parseInt(year), 
            parseInt(month) - 1, 
            parseInt(day),
            parseInt(hours), 
            parseInt(minutes), 
            parseInt(seconds)
        ));
        
        return { date, timezone, hours: parseInt(hours), minutes: parseInt(minutes), seconds: parseInt(seconds) };
    }

    /**
     * Parse relative time format
     */
    function parseRelativeTime(relativeStr) {
        const match = relativeStr.match(/^([+-])(\d+):(\d+):(\d+)$/);
        if (!match) return null;
        
        const [_, sign, hours, minutes, seconds] = match;
        const totalSeconds = (parseInt(hours) * 3600 + parseInt(minutes) * 60 + parseInt(seconds)) * (sign === '+' ? 1 : -1);
        
        return totalSeconds;
    }

    /**
     * Format seconds to relative time string
     */
    function formatRelativeTime(totalSeconds) {
        const sign = totalSeconds >= 0 ? '+' : '-';
        const absSeconds = Math.abs(totalSeconds);
        const hours = Math.floor(absSeconds / 3600);
        const minutes = Math.floor((absSeconds % 3600) / 60);
        const seconds = absSeconds % 60;
        
        return `${sign}${hours}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    }

    /**
     * Add time to a date or current time
     */
    function addTime(baseValue, amount, unit) {
        const isRelative = baseValue && baseValue.match(/^[+-]/);
        
        if (isRelative) {
            // Add to existing relative time
            const existingSeconds = parseRelativeTime(baseValue) || 0;
            let additionalSeconds = 0;
            
            switch(unit) {
                case 'days': additionalSeconds = amount * 86400; break;
                case 'hours': additionalSeconds = amount * 3600; break;
                case 'minutes': additionalSeconds = amount * 60; break;
            }
            
            return formatRelativeTime(existingSeconds + additionalSeconds);
        } else {
            // Add to absolute time
            let baseDate = new Date();
            let timezone = null;
            
            if (baseValue) {
                const parsed = parseTimestamp(baseValue);
                if (parsed) {
                    baseDate = parsed.date;
                    timezone = parsed.timezone;
                }
            }
            
            switch(unit) {
                case 'days': baseDate.setUTCDate(baseDate.getUTCDate() + amount); break;
                case 'hours': baseDate.setUTCHours(baseDate.getUTCHours() + amount); break;
                case 'minutes': baseDate.setUTCMinutes(baseDate.getUTCMinutes() + amount); break;
            }
            
            return formatTimestamp(baseDate, timezone);
        }
    }

    /**
     * Convert between absolute and relative formats
     */
    function toggleFormat(input) {
        const value = input.value.trim();
        if (!value) return;
        
        const startTimeInput = document.getElementById(FIELD_IDS.startTime);
        const startTime = startTimeInput?.value ? parseTimestamp(startTimeInput.value) : null;
        
        if (value.match(/^[+-]/)) {
            // Convert relative to absolute
            if (!startTime) {
                // Can't convert without reference time
                return;
            }
            
            const relativeSeconds = parseRelativeTime(value);
            if (relativeSeconds === null) return;
            
            // Add relative seconds to start time using UTC
            const resultDate = new Date(startTime.date);
            resultDate.setUTCSeconds(resultDate.getUTCSeconds() + relativeSeconds);
            
            input.value = formatTimestamp(resultDate, startTime.timezone);
        } else {
            // Convert absolute to relative
            if (!startTime) {
                // Default to +0:00:00 if no reference
                input.value = '+0:00:00';
                return;
            }
            
            const parsed = parseTimestamp(value);
            if (!parsed) return;
            
            // Calculate difference in seconds
            const diffSeconds = Math.floor((parsed.date.getTime() - startTime.date.getTime()) / 1000);
            input.value = formatRelativeTime(diffSeconds);
        }
    }

    /**
     * Create a button
     */
    function createButton(text, iconClass, cssClass, clickHandler) {
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = `btn ${cssClass}`;
        btn.innerHTML = `<i class="fas ${iconClass}"></i>${text}`;
        btn.onclick = clickHandler;
        return btn;
    }

    /**
     * Create a dropdown with time options
     */
    function createTimeDropdown(label, unit, input) {
        const dropdownBtn = createButton(label, 'fa-plus', 'btn-outline-secondary dropdown-toggle', null);
        dropdownBtn.setAttribute('data-bs-toggle', 'dropdown');
        
        const dropdownMenu = document.createElement('ul');
        dropdownMenu.className = 'dropdown-menu';
        
        TIME_INCREMENTS[unit].forEach(amount => {
            const li = document.createElement('li');
            const a = document.createElement('a');
            a.className = 'dropdown-item';
            a.href = '#';
            a.textContent = `+${amount} ${unit === 'minutes' ? 'min' : unit === 'hours' ? (amount === 1 ? 'hour' : 'hours') : (amount === 1 ? 'day' : 'days')}`;
            a.onclick = (e) => {
                e.preventDefault();
                const currentValue = input.value.trim();
                const fieldIsRelative = !ABSOLUTE_ONLY_FIELDS.includes(input.id) && 
                                      (currentValue === '' || currentValue.match(/^[+-]/));
                input.value = addTime(currentValue, amount, unit);
                input.dispatchEvent(new Event('change'));
            };
            li.appendChild(a);
            dropdownMenu.appendChild(li);
        });
        
        const container = document.createElement('span');
        container.appendChild(dropdownBtn);
        container.appendChild(dropdownMenu);
        return container;
    }

    /**
     * Enhance an input field with date/time picker
     */
    function enhanceField(input) {
        // Create wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'date-time-picker-wrapper';
        input.parentNode.insertBefore(wrapper, input);
        wrapper.appendChild(input);
        
        // Create button group
        const buttonGroup = document.createElement('div');
        buttonGroup.className = 'btn-group btn-group-sm mt-1';
        
        // Now button
        const nowBtn = createButton('Now', 'fa-clock', 'btn-outline-secondary', () => {
            input.value = formatTimestamp(new Date(), null, true);
            input.dispatchEvent(new Event('change'));
        });
        buttonGroup.appendChild(nowBtn);
        
        // Time increment dropdowns
        buttonGroup.appendChild(createTimeDropdown('Days', 'days', input));
        buttonGroup.appendChild(createTimeDropdown('Hours', 'hours', input));
        buttonGroup.appendChild(createTimeDropdown('Minutes', 'minutes', input));
        
        // Format toggle (only for relative-capable fields)
        if (!ABSOLUTE_ONLY_FIELDS.includes(input.id)) {
            const formatBtn = createButton('Format', 'fa-exchange-alt', 'btn-outline-secondary', () => {
                toggleFormat(input);
                input.dispatchEvent(new Event('change'));
            });
            formatBtn.title = 'Toggle between absolute and relative time format';
            buttonGroup.appendChild(formatBtn);
        }
        
        wrapper.appendChild(buttonGroup);
    }

    /**
     * Initialize all date/time fields
     */
    function initialize() {
        Object.values(FIELD_IDS).forEach(fieldId => {
            const input = document.getElementById(fieldId);
            if (input && !input.dataset.datePickerInitialized) {
                enhanceField(input);
                input.dataset.datePickerInitialized = 'true';
            }
        });
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initialize);
    } else {
        initialize();
    }

    // Export for dynamic initialization
    window.initializeDatePickers = initialize;
})();