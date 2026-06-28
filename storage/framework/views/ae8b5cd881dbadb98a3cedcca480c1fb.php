<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($whatsappNumbers) && $whatsappNumbers->isNotEmpty()): ?>
    <div x-data="{ open: false }" class="floating-wa-wrapper">
        <button @click="open = !open"
                class="floating-wa-button"
                :class="{ 'is-active': open }"
                title="Hubungi Kami"
                aria-label="Hubungi Kami">
            <svg viewBox="0 0 24 24" fill="white" width="28" height="28">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
        </button>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-90"
             @click.outside="open = false"
             class="floating-wa-popup">

            <div class="floating-wa-popup-header">
                <span> Hubungi Kami</span>
                <button @click="open = false" class="floating-wa-close">&times;</button>
            </div>

            <div class="floating-wa-popup-body">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $whatsappNumbers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="https://wa.me/<?php echo e($wa->number); ?>"
                       target="_blank" rel="noopener noreferrer"
                       class="floating-wa-item"
                       @click="open = false">
                        <span class="floating-wa-item-icon">🟢</span>
                        <div class="floating-wa-item-text">
                            <span class="floating-wa-item-label"><?php echo e($wa->label); ?></span>
                            <span class="floating-wa-item-number"><?php echo e(substr($wa->number, 0, 4)); ?>*****<?php echo e(substr($wa->number, -2)); ?></span>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <?php $__env->startPush('styles'); ?>
    <style>
        .floating-wa-wrapper {
            position: fixed;
            bottom: 24px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .floating-wa-button {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #25D366;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3);
            transition: all 0.3s ease;
            animation: wa-pulse 2.5s ease-in-out infinite;
            position: relative;
        }

        .floating-wa-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(37, 211, 102, 0.5);
            animation: none;
        }

        .floating-wa-button.is-active {
            transform: scale(0.95);
            animation: none;
        }

        @keyframes wa-pulse {
            0% { transform: scale(1); box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3); }
            50% { transform: scale(1.05); box-shadow: 0 4px 25px rgba(37, 211, 102, 0.5); }
            100% { transform: scale(1); box-shadow: 0 4px 15px rgba(37, 211, 102, 0.3); }
        }

        .floating-wa-popup {
            position: absolute;
            bottom: 70px;
            right: 0;
            width: 280px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            overflow: hidden;
            margin-bottom: 8px;
        }

        .floating-wa-popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 14px 16px;
            background: #075E54;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .floating-wa-close {
            background: none;
            border: none;
            color: white;
            font-size: 22px;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }

        .floating-wa-popup-body {
            padding: 4px 0;
        }

        .floating-wa-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            text-decoration: none;
            color: #333;
            transition: background 0.2s;
        }

        .floating-wa-item:hover {
            background: #f5f5f5;
        }

        .floating-wa-item-icon {
            font-size: 20px;
            flex-shrink: 0;
        }

        .floating-wa-item-text {
            display: flex;
            flex-direction: column;
        }

        .floating-wa-item-label {
            font-weight: 600;
            font-size: 14px;
        }

        .floating-wa-item-number {
            font-size: 12px;
            color: #999;
        }

        @media (max-width: 640px) {
            .floating-wa-wrapper {
                bottom: 16px;
                right: 16px;
            }
            .floating-wa-button {
                width: 48px;
                height: 48px;
            }
            .floating-wa-button svg {
                width: 24px;
                height: 24px;
            }
            .floating-wa-popup {
                width: 260px;
                right: 0;
                bottom: 60px;
            }
        }
    </style>
    <?php $__env->stopPush(); ?>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH D:\Project\Pelatihanku\resources\views/components/floating-whatsapp.blade.php ENDPATH**/ ?>