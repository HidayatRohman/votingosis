import {
    queryParams,
    type RouteQueryOptions,
    type RouteDefinition,
    type RouteFormDefinition,
} from '../../wayfinder';
import base, { show } from '@/routes/two-factor';

// --- Two Factor Challenge (Login) ---
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
});

store.url = (options?: RouteQueryOptions) => {
    return '/two-factor-challenge' + queryParams(options);
};

const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
});

store.form = storeForm;

// --- Two Factor Confirmation (Enable verification) ---
export const confirm = (
    options?: RouteQueryOptions,
): RouteDefinition<'post'> => ({
    url: confirm.url(options),
    method: 'post',
});

confirm.url = (options?: RouteQueryOptions) => {
    return '/user/confirmed-two-factor-authentication' + queryParams(options);
};

const confirmForm = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'post'> => ({
    action: confirm.url(options),
    method: 'post',
});

confirm.form = confirmForm;

// --- Enable / Disable 2FA ---
export const enable = (
    options?: RouteQueryOptions,
): RouteDefinition<'post'> => ({
    url: enable.url(options),
    method: 'post',
});

enable.url = (options?: RouteQueryOptions) => {
    return '/user/two-factor-authentication' + queryParams(options);
};

const enableForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: enable.url(options),
    method: 'post',
});

enable.form = enableForm;

export const disable = (
    options?: RouteQueryOptions,
): RouteDefinition<'delete'> => ({
    url: disable.url(options),
    method: 'delete',
});

disable.url = (options?: RouteQueryOptions) => {
    return '/user/two-factor-authentication' + queryParams(options);
};

const disableForm = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'delete'> => ({
    action: disable.url(options),
    method: 'delete',
});

disable.form = disableForm;

// --- Two Factor Setup Data ---
export const qrCode = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: qrCode.url(options),
    method: 'get',
});

qrCode.url = (options?: RouteQueryOptions) => {
    return '/user/two-factor-qr-code' + queryParams(options);
};

export const secretKey = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: secretKey.url(options),
    method: 'get',
});

secretKey.url = (options?: RouteQueryOptions) => {
    return '/user/two-factor-secret-key' + queryParams(options);
};

export const recoveryCodes = (
    options?: RouteQueryOptions,
): RouteDefinition<'get'> => ({
    url: recoveryCodes.url(options),
    method: 'get',
});

recoveryCodes.url = (options?: RouteQueryOptions) => {
    return '/user/two-factor-recovery-codes' + queryParams(options);
};

export const regenerateRecoveryCodes = (
    options?: RouteQueryOptions,
): RouteDefinition<'post'> => ({
    url: regenerateRecoveryCodes.url(options),
    method: 'post',
});

regenerateRecoveryCodes.url = (options?: RouteQueryOptions) => {
    return '/user/two-factor-recovery-codes' + queryParams(options);
};

const regenerateRecoveryCodesForm = (
    options?: RouteQueryOptions,
): RouteFormDefinition<'post'> => ({
    action: regenerateRecoveryCodes.url(options),
    method: 'post',
});

regenerateRecoveryCodes.form = regenerateRecoveryCodesForm;

// Compose with generated base routes (e.g., show)
const twoFactor = {
    ...base,
    store,
    confirm,
    enable,
    disable,
    qrCode,
    secretKey,
    recoveryCodes,
    regenerateRecoveryCodes,
};

export default twoFactor;
export { show };