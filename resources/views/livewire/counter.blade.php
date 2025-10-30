<div style="text-align: center; padding: 50px; font-family: Arial, sans-serif;">
    <h1 style="color: #333; margin-bottom: 30px;">Livewire Counter Example</h1>
    
    <div style="font-size: 72px; font-weight: bold; color: #007bff; margin: 30px 0;">
        {{ $count }}
    </div>
    
    <div style="display: flex; gap: 15px; justify-content: center; margin-top: 30px;">
        <button 
            wire:click="decrement" 
            style="padding: 15px 30px; font-size: 18px; background: #dc3545; color: white; border: none; border-radius: 5px; cursor: pointer;">
            - Decrement
        </button>
        
        <button 
            wire:click="increment" 
            style="padding: 15px 30px; font-size: 18px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
            + Increment
        </button>
    </div>
    
    <p style="margin-top: 30px; color: #666; font-style: italic;">
        lorem ipsum dolor sit amet
    </p>
</div>
