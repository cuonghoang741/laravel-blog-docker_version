@extends('layouts.app')

@push("styles")
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style>
        .btn-group-item {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 12px 20px;
            width: 140px;
            height: 48px;
            border: 1px solid #E6E6E6;
            border-radius: 12px;
        }

    </style>
@endpush

@section('content')
    <div class="container min-vh-100">
        <div class="d-flex justify-content-between align-items-center">
            <a href="/ai/trip-planner" class="d-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i>
                Planner AI
                <svg class="ms-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <rect width="18" height="18" fill="url(#pattern0)"/>
                    <defs>
                        <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                            <use xlink:href="#image0_2006_1091" transform="scale(0.00195312)"/>
                        </pattern>
                        <image id="image0_2006_1091" width="512" height="512" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAgAAAAIACAYAAAD0eNT6AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAAOxAAADsQBlSsOGwAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAACAASURBVHic7d13tGZVefjx770zw3QYeu9lAKXaEEURC2okGlRcMZEYY4kaJYkmYov8jEl0RaMY1IgNEYxdggUEBARLoogg0oYuTWBgmF6Ymfv7Y9+buU7mzr33fZ+z9ynfz1p7ITjzvHuftp+zzzl7DyCprfYBDgf2G1V2BGYC84DZw39uOfAosBJ4ALh1VPkVcEfWWkvKYqB0BSSF2QN4EXDMcNk1KO69wBXAlcB3gbuD4kqSpB7tDLwV+DGwHhiquKwf/q23ADtlaJ8kSRrlSOBsYA3Vd/pjlXXAd4CnVtxWSZI673mkofhSnf5Y5QrgORW2W5KkTjoQ+BrlO/rxysXAIRVtA0mSOmMO8O/AY5Tv3CdaHgNOZ8NXBpIkaRKOAm6mfIfea7kdeEb4VpEkqaWmAB8E1lK+E++3rAX+abhNkiRpDHOB8ynfcUeXC0mTD0mSpI3sC1xP+c66qrKA9DKjJEkadhjwEOU76arLg8ChQdtMkqRGO5xudP4j5RHgiSFbTpKkhjqS1CGW7pRzl4WkUQ9JkjpnF+AeynfGpcp9wG59b0VJkhpkDnAN5Tvh0uVqnDBIktQRA8B5lO9861K+iUuTS9l50kn5vQX4eMbfexD4EfAr0qd4dwKLgKXD//9cYGtgL2A+6aXEY4HtM9bxTcCnMv6eJElZHQysoPq76ruBfyG9aNdLoj9ASgQ+OByr6vouJyUfkiS1zjTgl1Tbkf4SeCkwGFjvweGYV1dc958DUwPrLUlSLfw11XWevyV10lUaAF5GtSMCf1VxGyRJymo7qvnefz3pfYKcb9LPAc4IbsdIWQhsk68pkiRVq4oO8xHgJTkbsZETgUc3Ua9+y+k5GyFJUlX2Bh4jtpO8m/RCYWmPJ34yozXAnjkbIUlSFT5ObAd5C7BH1hZs3p7ArcS28WNZWyBJUrBtgGXEdYwPAPtnbcHE7EOa2jeqncuAbbO2QJKkQO8hrlNcQfo2v66OBFYS195T81ZfkqQ4txDXIb42c9178ZfEPuqQJKlxnkxcZ/jNzHXvR+Q6B0/IXHdJkvr2MWI6weU066343UnrDES0/cOZ6y5JUt+iPo9r4rPwqHcf7spdcUmS+jGfmA7wYdJqfU2zJXEzH+6bue5SJ0QuGCJpg2cGxTmDDcv2NskS4BNBsY4NiiNJUuXOpf873/XAXpnrHWlPYB39b4cv5q64JEm9up3+O74fZq91vMvpfzv4OaBUAR8BSPFmEPPW/nkBMUr7dkCMvYHpAXEkjWICIMXbj5hz69KAGKVdFhBjCr4IKIUzAZDiRczVvxC4ISBOadeRvgbo1/yAGJJGMQGQ4u0dEONG0vPvphsitaVfEdtU0igmAFK8eQExbg6IURcLAmJsFRBD0igmAFK8LQNi3BcQoy7uCYgRsU0ljWICIMWL6KyaOPnPWJYFxGjibIhSrZkASPFmBcRYHhCjLiKSmYhtKmkUEwAp3uqAGG367n1GQIxVATEkjWICIMWLuHufExCjLiIeibRpRESqBRMAKV5EZ7VdQIy62DYgxoqAGJJGMQGQ4i0JiHFAQIy6iGhLxDaVNIoJgBTvzoAYbZr5LqItdwbEkDSKCYAU746AGHsDOwXEKW1nYpY0vjUghqRRTACkeLcFxBgAjg2IU9qzg+JEbFNJo5gASPF+BywOiPOCgBilPT8gxsOkxZEkSaq9C0gL4fRTltHszwFnkyYB6nc7nJ+74lIXOAIgVeMnATFmAycGxCnl5cQkMD8LiCFJUhbPov873yHgepqZqA8A1xGzDY7JXHdJkno2k5jh7yHgxZnrHuFEYtq+hJiphCVJyuYrxHSCC2jW2gDTgZuIafs5mesuSVLfXkZMJzgEvCtz3fvxD8S1u4mjH5KkjptNepM/oiNcCRyRt/o9eQJp5b6o4f+ZeasvSVKMTxN3N7yAmJX1qrIVaca+qPZ+Im/1JUmKczCwnrhO8YfU832AacCFxLVzPXBg1hZIkhTsYuI6xiHgy8CUrC3YvCnAfxLbxguytkCSpAo8j9jOcQj4JvUYCZgBfIv49kWtISBJUlGXEN9J/gjYMWcjNrIzcOUm6tVv+UHORkiSVKUnEPsuwEi5nzJ3y88lLXoU3Z51wGEZ2yFJUuXOIb7DHCIlFp8Hts/Qhh2As6gmmRkaji1JUqvsCDxENR3nEPAIaRKerSuo+9bA+4BFFdb/AWC7CuouSVJxr6C6DnSkLAbOAI4KqO9RpO/xF2eo90sD6itJUm19neo705FyO/AZ4JXAQcAWm6nXFsN/5pXDf+f2jPX86iS2n6QAA6UrIHXQ1sDPgf0K/PZa4D7S44Jlw/9tDrANsAswtUCdFgBPAR4t8NuSJGV1IKnDy3WHXdeyBHhcn9tSkqRGeQnVvU3fhLIOOKHvrShJUgP9Bd1MAtYDbwzYfpIkNdYplO+Qc5e3h2w5SZIa7lTKd8q57vzt/CVJGuXVwGrKd9JVldXAyVEbS5KkNjmOamfbK1UeAY6N20ySJLXPgcCvKN9pR5WrgP1Dt5AkSS01DTiN9Klc6Q6817IeOJ3Nzz4oSZI24XnAbZTvzCdbbqHMMsWSJLXGNNKngkso37GPV5aRRi5mVLEhJEnqol1Ji/SsonxHv3FZCfwHaT0BSZJUgR1Jd9l1+FpgCek5/65VNliSJG2wFfBa4DLyviy4DrgUeA2wZeWtlCRJY9odeBtwAek5fHSnvxT4PvA3wG6Z2iRJkiZhGvA04CL67/h/MBxratYWSKrcYOkKSAr3GPAT4OqAWFcPx1obEEtSjZgASJLUQSYAkiR1kAmAJEkdZAIgSVIHmQBIktRBJgCSJHWQCYAkSR1kAiBJUgeZAEiS1EEmAJIkdZAJgCRJHWQCIElSB5kASJLUQSYAkiR1kAmAJEkdZAIgSVIHmQBIktRBJgCSJHWQCYAkSR1kAiBJUgeZAEiS1EEmAJIkdZAJgCRJHWQCIElSB5kASJLUQSYAkiR1kAmAJEkdZAIgSVIHmQBIktRBJgCSJHWQCYAkSR1kAiBJUgeZAEiS1EEmAJIkdZAJgCRJHWQCIElSB5kASJLUQSYAkiR1kAmAJEkdZAIgSVIHmQBIktRBJgCSJHWQCYAkSR1kAiBJUgeZAEiS1EEmAJIkdZAJgCRJHWQCIElSB5kASJLUQSYAkiR10NTSFei4ecAewLbA9sP/PhWYW7JSao2jgmK8IyCOtBRYCywCFg6Xu4FHS1aqywZKV6Aj5gJPBQ4DDgUOBvYhdfiS1GWLgDuA64HrgGuAnwHLSlaqC0wAqjGV1Nk/Z7g8A9iiaI0kqTnWkRKBS4bLj4FVRWvUQiYAcWYDJwJ/SurwZ5StjiS1xirgcuBc4FvAiqK1aQkTgP4MAkcDrwL+GJ/dS1LVVgLfBb4EfJ80WqAemAD0Zg7wBuAUYPfCdZGkrvotcDrwaWB54bo0jgnA5MwFXgOcCuxUuC6SpGQh8AlSMrCocF0awwRgYrYB/g54E7Bl4bpIkjZtMXAG8BFMBMY1pXQFam4AOBn4L+B4YHrZ6kiSNmMG6SXs1wGrgV8AQ0VrVGOOAIztUOCTwNNKV0SS1JMrgTeT5hfQRhwB+L9mAx8CPgfsVbYqkqQ+7Am8lvTo9sekmQg1zBGA33cg8DXgkNIVkSSFuhE4CfhN6YrUhYsBbXAycBV2/pLURgcBPye9HyAcAQCYCXycNEwkSWq/LwFvpONzB3Q9AdgNuAB4fOmKSJKy+jXwAuC+0hUppcsJwIHAD0jL8UqSuucu0ifeN5euSAldfQfgScAV2PlLUpftCfyUtFx753RxBOB44Jukz/3q4l7gfuBR0hrYy3C1K0ntMYu0hsocYB6wM7Br0Rr9vmWk1VwvLl2RnLqWADwH+B6wRaHfH1nj+nLgamABaehpaaH6SFIpc4H5wAHAEcCzgMMpNz/NauAPgB8W+n1V6InAEtK0kDnLI6SVql4CbF15KyWpubYmXSs/Tbp25r5eLwaOrLyVyuoA4EHyHURrgPOBl+H6AZLUi+mka+j5pGtqruv374D9MrRPGewC3EGeA2c1cDYePJIUaQ/SUr8ryHMtv530noIabCbwK6o/WFYA/wrslKdZktRJOwMfBlZS/XX9KhzBbbQzqf4guYT0IoskKY99SC90V319PyNXgxTrj6n2wLgL+KNsrZEkbexE4LdUe61/ebbWKMR+pLc5qzogzsM3+iWpDrYEvkp11/slOMrbGDOo7rn/KuAt+ZoiSZqAAeCtpGt0Fdd+3wdoiNOo5gC4nzSXgCSpnp5E+oyvij7gPRnboR7sRzVvh94O7J+xHZKk3uxNmmU1uh9YQXr5UDVVxVuh11GvOaslSZu3I2m69ej+4IKcjdDEvZT4nX0taeEKSVKzzCNdw6P7hRfnbITGNxu4m/hhf2eCkqTmqmIm2DtJqxuqJt5O7A5+ED/7kKQ22Jf4FwP/OmsLNKYZwH3E7dg1wNFZWyBJqtLTgceI6yfuwc8Ca+FNxGZ2f5e3+pKkDE4ltq94Q97qa2PTiH2+833ShBKSpHYZIC0tHNVf3A5MzdoC/Z5XE7czHwC2y1p7SVJO25Pe8YrqN07OW32N9kvckZKkiXsNcf3GzzPXXcMOJm4nXolD/5LUBQPA5cT1H4/PWnsB8CFidt4a4HGZ6y5JKudQ4r4K+OfMde+8QeIm/vls5rpLkso7i5g+5B5gSt6qd9vxxOy4tcABmesuSSpvPrCOmL7kuMx177SziNlpX85cb0lSfXwVR5Ib515idtqhuSsuSaqNw4npS36bu+JddSAxO+xnuSsuSaqdq4jpU/bLXfF+DZauQA+inrV8KSiOJKm5ovoC3wPI4Ov0n6mtxln/JElpdsA19N+vfCV3xbtmEHiI/nfUebkrLkmqre/Rf7/yAA2bUK5pjwDmE3PnflFADElSO/wgIMYONOyz8qYlAIcHxbk0KI4kqfkuC4rTqC/LmpYAHBIQ437gpoA4kqR2+A1pCL9fEX1UNk1LAA4MiHF5QAxJUnsMAT8KiHNQQIxsmpYA7BMQ49cBMSRJ7XJdQIy9A2Jk07QEYK+AGDcHxJAktUtE32ACUJG5wFYBcRYExJAktUtEArANMDsgThZNSgAiPv9bB9waEEeS1C63AOsD4jRmkrmuJQCLSLMASpI02kpgSUCcbQNiZNGkBGDLgBjLAmJIktopIgGYFxAjiyYlADMCYiwNiCFJaqeIPmKLgBhZNCkBiNioJgCSpLFEjABE3Kxm0aQEYEpAjJUBMSRJ7bQiIMa0gBhZNCkBiFhlaSgghiSpnSL6iMasCNikBECSJAUxAZAkqYNMACRJ6iATAEmSOsgEQJKkDjIBkCSpg0wAJEnqIBMASZI6yARAkqQOMgGQJKmDTAAkSeogEwBJkjrIBECSpA4yAZAkqYNMACRJ6iATAEmSOsgEQJKkDjIBkCSpg0wAJEnqIBMASZI6yARAkqQOMgGQJKmDTAAkSeqgqaUroFabAcwGtgKWA8uG/ylp02YDc4b/uZh0vqwqWiO1lgmA+rU/8ERg/nA5ANgTmAdMGePvLALuB24CFgA3A78CrgPWV1xfqaRB4DDgCNK5cgBwILATsPUYf2ctKRm4i3Su3Ew6d64Cbqu4vmoxEwBN1k7A84HjgGcBu/UQY+vhcvBG//1h4HLgMuAC4PaeaynVx/7AC0jnyzMZu6Mfy1Rg2+Fy5Eb/32+BS4fLhcBDfdVUqqmXA0N9louz17odpgMnAF8D1tD/fphouQo4Bdiu+iZKoeYBrwd+TBrVynG+rCVd404GZlXfxFa6mP73w0nZa90BJgD57QL8G/Ao+Tr9TZWVwFmkoVKpzg4BzgVWU/aceQT4ILBjtc1tHROAmjIByGcv4HRgBWUvYhuXdcB3SO8cSHVyOHA26RgtfZ6MLquATwO7V9f0VjEBqCkTgOrNAk4jXTRKX7g2V9aTLrY7VLIVpInbmXQslj4nxitrSEn97Go2Q2uYANSUCUC1TgDupPyFajJlEekdgbG+NpCqMpV07C2m/HkwmXIb8MIKtkdbmADUlAlANeYB36D8hamf8hNgj+gNI41hX+AXlD/u+ylfAuZGb5gW6FQCUOIzwF3Z8M34lqRPYmYDW4zz9/apuF5d9ATgq6QLWpMdDfwaeC0pmZGq8kfA55j8p3x186fAk4FXANcUrkvbvA549jh/Zg1pkqdFpFGkkflQ7q22ankNAk8C3kH6RnUJZbNeRwA2+CvKv6kcXdYD/4qPBBRvKvAJyh/j0WUl8BeB26npIkYA+imLSXOg/D3pZedGTtd/EOllstspf4CPLiYAMEDaN6X3RZXl26RpiKUI04FvUv64rrJ8MGxrNVvpBGDjcg/p5c3Dq2x0lOcDV1B+o41Vup4ATAHOpPx+yFEuI61BIPVjHnAl5Y/nHOXzODts3RKA0eVHwPHVNb13xwO/pPwGGq90OQGYSvvvYjYuV2ESoN5tQ3q3pPRxnLN8mYYOOwepcwIwUn4OPKeqDTAZu9CMb2BHSlcTgAHgs5Tf/iXK5fg4QJM3kzSNb+njt0T5VMD2a6omJAAj5TsU/Prp1cDSzVSujqWrCcAHKb/tS5b/wqFNTdw04HuUP25Llvf2vRWbqUkJwBDpxfpXVbIlxjCT5j5H7mIC8EbKb/c6lNP73ZDqjM9Q/nitQ/mzfjdkAzUtARgpZ5NhlsddafYzsa4lAEdSflrftaSFSerwyeHL+tuc6oCTKX+criadM2sL12M58Lj+NmfjNDUBGCLN57DzZBo7mWHRfYCLaP6kMV2xJWmSn+mZfm8R6S3VK4EbSJNa3E9KQEZMI83ffwBpIqijgeNIiWUOnwWuJn2eKm3sQOCTGX/vHtLXKj8hTQSzAHgQeGzUn5lJuqgfABwMPGO45JiIaBZpCfAnk5IB1dthwE9JX+PdHBn48aQDs3SG02/p0gjAl6l+ey4hfTp0LP29OXwY8GHgvgx1/gUpEZFGm06e0c37SJNVHdpHXacAzwK+QJ7J1T7XR12bpskjACPlQQJHbnYD7qpBoyJKVxKA46l2Oz5Emkwo+i5kGmkI9qaK6//24Hqr+f6Bao+520iLB0V/kTJ3OO79Fdf/uOB611UbEoAh0pTCe/W7Mbaj+otxztKFBGAm6WJTxfZ7jPQy3ZYVt6HqldaWA3tW3AY1x56kY6KKY20ZaSr0qr9CmU1Kyqt61+Zm8j1OLKktCcAQcCOwba8bYpA0f3/pRkSWLiQA76eabXcDaag+p12o7oR00SCNqOqTvx8wyZeyAhxBuvBX0Z53ZmxHKW1KAIaA79Pj49l31aDy0aXtCcCupMU9orfbF8nwickYBknfJK8bp469lGMytkP19Dzij6t1pOtnqRn15gDnjFPHXsoyYPuM7SihbQnAEGkEalKOIg33lq54dGl7AvBR4rdZXRYJOZH45ObCrC1QHUWvXbKatMRuHbyD+OvBP2VtQX5tTAAeI33JMSFTSJ9Kla50FaXNCcD2pAw9cnv9TdYWjO944uc1eFLWFqhOnk7ssbSKNKJQJ28jto2LSQsktVUbE4Ah0lo9/2eZ9E29mPJm0nOkqt1K+uzmDtJBtWacP38o8MqqK9VgpxA7TP8B0ohCnfyANO3lV4gbXn038JKgWGqWdwfGWgf8CWmulDr5CGlRo3cFxdsSeAvwj0Hx2uhc4Lpx/swWpERqb1LfVvX8OkeSZoU9Y3N/aB7wKNVlIVeTDp5eJn45KeD32zoCMA14gLj9dG7e6k/a3xPX1nXA7nmrrxrYB1hP3HH0trzVn5QBUtIc1dZ72MTdZEtEjAC8vIff3Q14K/CrgN8fqzzCOKujvreiH74KeG4PG2U0E4CxnUDcvrqF6j/z69cAcB5xbT41b/VVA6cRd/x8l3RM1tkcYj/p7vd6XlelEoDRjqe6RGDMkaDZpAleIn9sNek5ckS2aAIwtq8Rs7/WAk/MXPdebQ8sJKbd12euu8oaID2CjDh2HqKPb60zewpxX9N8KXPdc6lDAgCpz3w78fM6PMgYj4rfFPxDDzCJNw8nwARg0+YR93b8Zp8P1dDriDtem5L4qH9PI+64eU3muvfrP4hp9zLSqELb1CUBGHEU8dPwv3FTP/TfgT9wL7B/0AYYYQKwaS8lZp8tonlv9w4SN1T2vsx1Vzn/TMwxcxX1H/rf2DbEzbD5osx1z6FuCQCkhaDuDajXSPnZSOCRN6nnk4aHIiwmPcO4JSieNi9qju4zSC+ANsl60sU8QlfmOlfcvv4A6YLaJI8Qt+Kh50weC4AXkBZ+ivAUNrpBj1wIIzr7GeEIwKbdQP/bZSXNneFrkDRPeb/bYDVp+VO125bETHJ2I+Vm+uvXDsTMp3FN7opnUMcRgBGvCKjbSHkPbDiAnxNUwa8AXw+KpfHtTFrDvF/nk15maqL1wFkBcbYgTQyjdjuGmIV5vkA69proQeA7AXEOpbk3Dk30VeLWMHk2pARgFjHD/6twmdXcjiDmGeTZATFKOoeYi/ETAmKo3o4MiLGO+s+VMZ6It/gHyDNpnDb4W9JoZb+eCswcBI4m3f306wukFxWUz/yAGKuAHwbEKelu4NqAOAcExFC9RZwzV9P8a91FpHO/XxHbUxN3NzEjntOBpw4ChwQEg/R5ifKKOPl+TMyFoLRLA2JEPE5RvUWcMxHHWmmrSF9+9csEIL9PBcV5/CAxdz3Xkeb1V14R++4nATHq4MqAGF7M2s9zZgPPmWa6lvTyd7/mDxJz13NJQAxN3h4BMW4MiFEHEe3YGpgbEEf1NI+Yaa49ZzaIuAZp8iL63PmDwC4BgX4REEOTF9FZLQiIUQd3kD7v6lfd10FQ7yLOlzXAnQFx6iDi3Pd8KePnATF2HSTmpLg5IIYmL+Lk+11AjDp4jDTJSb8cAWiviH27kLRmRhtEnPueL2VE9LlzoxKApn5D3mRTgRkBcZYGxKiLiLZ4QWuviH27LCBGXUScL7No79LAdRbR584dmQegXysCYmhyomatWxkUpw6WB8TY5EpZagWvdb8v4nwZwHOmhIhEdM4gMdNZNnVGrCaLuhBFjCLUhRd4bU5EsjszIEZdRN1EeM7kF9HnDjZ1Lmul55ARM0K1acg7oi1teiSi3+cjot8X8Q7RStrzTkTnmAA0W8TqUDsGxKiDqaSlTvtlAtBeEft2W9rzzHuHgBieLw1mAtBsESdfWyby2JuYKa29oLVXxL6dDuwVEKcOIs79qCVqVYAJQLPdHRCjLdPfRrRjCbA4II7q6VFikgDPmQ0irkEqxASg2SIm8nhaQIw6iFjK1/ks2m0IuCUgjufMBp4zDWYC0GwRJ9/TScOaTXdcQAwvZu0XsY+fFRCjtOmklWD75TnTYCYAzRZx8s0Cjg2IU9IuxKxL3pZpkTW2iHPmicBOAXFKejYxnzR6zjSYCUCz/SoozslBcUp5JTFvZkdtT9VXxD6eSjrmmuxVQXE8ZxrMBKDZ7iUmA38JaSW8JhoAXh0QZy1wRUAc1dsVwLqAOK8mHXtNtA3w4oA4NwL3B8RRISYAzXdZQIxZwFsC4pTwh8DjAuL8Aj9p6oJHiblrPQR4YUCcEk4hZvj/hwExVJAJQPNdGhTnrTRvac8B4N1BsSISKTVD1DnzHpo3CrAVccm+50zDmQA03yWkNcr7tS1wWkCcnF4FPCko1veD4qj+ovb1UTTvXYD3E/O4bxVxiZQKGgooOZ4fnxRQz4sz1LOEbxGzHx8DDstc915tDTxATLtvo3l3curdAHAnMcfO/cC8rLXv3RGkd10i2v31zHXP5WL63zYvz1DPrQPqOeQIQDt8KSjOVOBc4lYJq9JniZnLHNL2GwqKpfobAs4JirUT8JmgWFWaTWpz1DoGZwfFUWER2aAjAGVtASwkZl8OAZ/PW/1JeytxbV0P7Je3+qqB+cQdQ0PAm/NWf9K+SFxbHwKm5a1+No4AqHHWENtp/znwzsB4kV4EfCQw3g+BWwPjqRluJvYZ9seAPwiMF+ndxM718TnS40K1QERG6AhAeTsBK4i9M3591haM7xhgObF3bs/M2gLVyXHEHkvLiZlfP9IbSOdyZBujHr3VUadGAAgKYgJQD2cQe0FbT32+DDiB+M7/p1lboDr6MbHH1CrgxKwtGNspxHb+Q8BHs7YgPxOAHooJQD3sAawm9oQfAj5FuQWDBoC/JQ05Rrfr+RnboXr6A+KPq8dInW+pL0tmAGeOU8deyipgt4ztKMEEoIdiAlAfHyT+xB8Cfkl6cSqn7YDzAuq+qXJBxnao3iIu+psq3yLNr5HTgaSZDqtozwcytqMUE4AeiglAfcwC7qCaC8Aa4HTSJ0VVGiC9tPRgRe1YRf5kRvW1P+mYqOJYe4Q0GlD1C9czSY/rqmrHXVR/3teBCUAPxQSgXk6kmovASPkd8A7i5wsYJD3rv6ri+p8WXG813weo9pj7NSmpjfoOf8R00su6d1dc/z8MrnddmQD0UEwA6ufbVHtBGCJ9D3w6aX30fuwLvI80I1/Vdb6BmIVQ1C6zgJuo/vi7lXSs79NnfZ8EfJx0DlZd52/0WdcmMQHooZgA1M82xE13OpFyD2l2sNcBTyM9v9+ULUkJw58CnyQtKZqrjsuBx09mI6pTDiX2U9rxyg3AJ4A/AZ4AzB2jXtuRPi98HWnWynsz1vF2mjPVcQQTgB6KCUA9HUV6bp/rYrFxeYz0DPR20p1KVc8nJ1pe09/mVAe8nrLH6CrSuXI76dyp4uuXiZbVwJP725yN06kEwJkA2+2/gVML/v5U0oG6N+kuptSnhJBGJ+o+xbHKO5O0HkYp00nnyt6kc2dqwbr8HfDzgr+vipkAtN+/0f7JO8ZzGfWb1VD19RrgotKVKOwTpHcM1GImAN3wNrq7etdVwItJw5nSRKwBXgZcXboihfwnacEttZwJQDcMkV4g+kHpimR2M2mmt6WlyiffpAAAFO9JREFUK6LGWQq8EFhQuiKZXQD8GWkKYbWcCUB3rCF9y/uV0hXJ5CrgGaTJhKRePAAcTXqXpgu+RZpDxJX+OsIEoFvWAK8kvRfQZpcCz8bOX/17GHgu7R89O4P09vqq0hVRPiYA3TNEeifgVGBt4bpU4fPAC4AlpSui1lhGeo/ki6UrUoG1pLf934LD/p1jAtBdHyLdJd9buiJBVpLe9P8L0kiHFGk18GrS8/HlZasS5h7gOODDpSuiMkwAuu0K4AjgwtIV6dNvSDOpfaZ0RdR6Z5Mmx7m+dEX69H3SuX9l6YqoHBMAPUQaMj+J9NJTk6wE/h9pauEbC9dF3XEDqfP8a9LjgSb5HWkU40XAwsJ1UQ1ETBnpVMDtMI+0uM9ayk0/OtHyHWCvSraCNHG7AV+n/PkwXllHGr3YtprN0BpOBazOepS0dvmhwDnU8yXBi4FnkpYNvrNsVSTuIV3wnwX8sHBdNmUtqeN/HGk54ofLVkd1E5FdOgLQTvuS5kZfSfm7l/+iewuTqHmOAs4nHbMlz5kVwKfpf9nhrunUCABBQUwA2m0r0t3DxaRPhXJdxK4HTsOhfjXPLqTRtKvJ2/FfNfy7DvX3xgSgh2IC0B17AX9FmjXsYWIvXstJi7CcChyWqT1S1Q4H3km6/iwn9pxZCHwTeDOwZ64GtVinEoCSS02qme4kzRp2BukrksNJb+EfABwIzCclCeMdW3eT5upfANwEXAv8Dy7ao/a5Zrj8C2m536eQzpv5w+UAYPdxYqwlnXs3k86XBcAvSOeNE/ioJyYA6sd60hDnplZNmwnMAeaSvi5YOlyW0bxPp6Qoq0nzb1yxif9vDhvOmbmkl3JHzpmVuSqo7jABUFVWDpeHSldEaoiR5Ph3pSuibvAzQEmSOsgEQJKkDjIBkCSpg0wAJEnqIBMASZI6yARAkqQOMgGQJKmDBomZYGJmQAxJkjS+2QExVgySZprqV461ACRJUkyfu3SQmGlZ9w2IIUmSxrdfQIwlg8CDAYGOCIghSZLGd3hAjIcGSatK9eu4gBiSJGl8EX3ugkHS8pL9ejqwa0AcSZI0tl2ApwbEuWkQuCEg0CDwZwFxJEnS2F4DTAmIc+Mg8BNgKCDYKcCsgDiSJOn/mg28NSDOeuAnU0nrtf8GOKTPgDsA7wTe22ccqYn2AY4HDgK2Ir1cew3wfWBRwXo12Tzg+aSXjHcEFpPeWboQuK1gvaRS3g1sHxDn18DDI//yUdIoQL9lFTFvJ27KSQH1u7iiuqm79gW+Tsqoxzon/o3UmWlitgI+TJqkbFPbdD1wHnBgqQqqtS6m/37m5RXV7QhgdUD9hoCPjA789KCgQ6SXCquYGMgEQHXzQtLd/UTPi/3LVLNR9gduYmLbdCnwR2WqqZaqawKwDXBLQN1GytNGBx8gDalFBb+C+PcBTABUJ08CljO54+9O0qMybdq2wK1MbpuuAZ5dorJqpTomALOAHwfUa6TcSurz/3cxoCHgnMAKH0PakNsExpTqYgbwNSaf5O4JfAWYGl6j5psKfIPJzyo6DTibmLnRpbrZFriEje7Y+3QOqc//PbuRnldGZRlDwO3Ak4Mq7QiA6uKt9HccfoHhDFxA2hZn0d82/fvclVYr1WkE4CjSqGFkn7yKzczZc2bwjw0Ba4HTSZlMP0wAVBc/o/9j8RO4HDek75k/Qf/b85rcFVcr1SEB2A44g9R3RvfH/7G5H94XeKyCHx0ivbDzUXr/3NAEQHUwjbgT8/vAlnmrXyuzSW/zR2zL9TgPifpXMgE4FPgYaYG+KvrgNcDeo39w42eRt5EyhL/qsQGbMwf46+FyK3A5cN3w/14KrBjn7+9TQZ2kydqVmFm4AF4AXAa8lDTU1yV7At8EnhAUb4C0b24Jiif1ah/GP65nAXNJq/odChxL9avqfhK4Y7w/tCVwH9VkIKWLIwDq107EH5dLgDfRnfcC/piJfz45mbJ7zkaolSJGAOpY7mcSc5H8SQ0qXEUxAVC/ppJGrKo4Pi+j3SNdewLfpZpttxzYIl9T1FJtTQD+eLIb4ks1qHR0MQFQhO9R3TG6AvgQ7fqEdmtSm1ZQ3Xa7IFtr1GZtTAC+0suGmAPcWIPKRxYTAEX4A6o/VhcB/wzsnKlNVdgZ+BeqGe7fuJyQqU1qt7YlAAvo40Xjw0jPJ0s3IqqYACjCAGm2yxzH7GrSRDfH0YzPBgdJdT2b+HlFxio/ojvvT6habUoAFtP/In88i3wnctXFBEBR9gIWkvf4vQv4IGlWsKgvESJMIa0n8iFSHXNuk4dI7xZIEdqSAKwGnhu1UV5BNZMS5C4mAIr0DOJW5+ql4zsHeCNppC5nQjBl+DffBJxL/kRo9EXumIrbqm5pQwKwlgnORTDROcm/SjrZ/pM0D7qk9BjgDaSpfXPbjvS1zp8M//tS0mx4N5FWHryRNLfAg6QOutff2BHYAziYtPzuQaTvluf2GDPSG4ArS1dCqpHVwMmkJcrHNZlFSc4jTVxyHmm9bklpDvsdSMPfJc0l3Q1v6o54DWnE4EFgHfDo8H8f+efI98Fbka4J25PaVOfP6v6etO0lJcuAE6l4pPtg4DeUH+bopfgIQFV5J+WP7y6U9bjwj6rT1EcAvyaN0GUxg7TAT+lGT7aYAKhKr6a6tTQs6dnmGya6M6QeNDEBKLYc9gmkJX9Lb4CJFhMAVe0lVDdTYJfLEuDFk9gPUi+alADcDbysms0wcbOAfyJNxVl6g4xXTACUw3zaN4lWyXIL8PhJ7QGpN01IAJYB7wdmVrQNerIdcBp5Zv3qtZgAKJetga9R/phvevkqk1jEROpTnROAJaRH7ztV1voA84A3Az+j/AbbuJgAKLeXU++kuK5lCfD6Hra31I86JgA/Jc330bhE+ADg7aTFU+rwXNQEQCXsBZxP+eO/KeX84W0m5VaHBGAJadXMtwH7V9nYnPNnTyVNIDKf9LnCvqRvjecMl/HeYpxNmpSkH5cQOD2iNEknkIbw9i5dkZq6B3g36a1mqYSLgef0GeMB0jtxm7Oc9Cx/GWl+jtvYMInXr0lfvGiUk3AEQM03kzQq9hDl7zTqUh4k3e3U6qUmdVLECMCEpuGtgyasLia1yUrgw8A+wHvZMBtfFy0C3kPaFh8hbRtJmZgASGUsBT4A7E6a3OY3ZauT1S3AqaSO/59Iw6CSMjMBkMpaBpxJej/mucC3SQt6tM1q4FukNs4nrZ3Q5dEPqbjJLAYkqTpDpJdULyEtyvNi0rPE44FpBevVj3XAf5NWJhtZNlhSTZgASPWzmPQm/NmkCbaeDxw3XPYsWK+JuBO4dLhcCDxctDaSxmQCINXbQuCc4QKwHykReApwGPA40uJcJawErgeuJd3pX0paG0RSA5gASM1y63A5c/jfp5Im3DqUNF/+7sBuwC7AHqS1OvqxHPgtcD/pO/27getI3yovIA3zS2ogEwCp2dYCNwyXTdkK2JX0jf0sYDpp4q1pwNzhP7OUtIzxMtLLeitId/f3kh5HSGohEwCp3RZjJy5pE/wMUJKkDjIBkCSpg0wAJEnqIBMASZI6yARAkqQOMgGQJKmDTAAkSeogEwBJkjrIBECSpA4yAZAkqYNMACRJ6iATAEmSOsgEQJKkDjIBkCSpg0wAJEnqIBMASZI6yARAkqQOMgGQJKmDTAAkSeogEwBJkjrIBECSpA4yAZAkqYNMACRJ6iATAEmSOqhJCcC6gBhbBMSQJLXTjIAYjwXEyKJJCcCagBhzA2JIktopoo9YHRAjCxMASZKSLQNimABUYGlADBMASdJYIvqIZQExsmhSArAwIMY8mtVmSVIeU4CtAuJE9FVZNKkzfDggxnRgj4A4kqR22RuYFhDHBKACjwCrAuIcGBBDktQu8wNiLCfmcXUWTUoAhoDfBsSJ2MmSpHaJuDm8g9RXNUKTEgCA2wNiHBQQQ5LULhF9w50BMbJpWgKwICDGMwJiSJLa5ZiAGDcHxMimaQnAdQExDgJ2CYgjSWqHXYEDAuL8OiBGNk1LAKI27rFBcSRJzfecoDgmABW6FlgZEOfZATEkSe0Q0ScsB24IiKPNuIT0lmU/5RHSnACSpG6bASyi/37lgtwV71fTRgAALguIsTXwooA4kqRmewlplth+XRoQQ+N4Kv1nakPAebkrLkmqne8R06c8IXfFu2gqsJj+d9YaYMfMdZck1cfOwGPEPFaekrnufWviI4C1wBUBcaYBfxMQR5LUTH9Luqns12XAuoA4moCTiRmyWUJ6H0CS1C3bkvqAiL7kFZnr3mmzSQsuROy492WuuySpvH8kpg9ZDMzMXPfO+yIxO+8RYLvMdZcklbMD8CgxfchnM9ddpJmbInaeO1CSuuUs4voP15cpYJC0PHDEDlwPHJ23+pKkAp5OuuZH9B13AgNZa6//9Q/EZXHXEPM2qCSpnqaR5uuP6jfelbf6Gm0r4p7jDAH/krf6kqSM/pW4/uJRYmYQVB8+RNwOXQ+ckLf6kqQMXkDc0P8Q8P681dem7EhahSlqpz5IWhtaktQOuwEPEddPLAO2z9oCjenjxO3YIeB/gDlZWyBJqsIc4BfE9hEfztoCbdZuxI4CDAEXkl4YkSQ10xbARcT2DUuAnXI2QuN7F7E7eQj4T5q5XoIkdd0AcRPGjS5vy9kITcwWwI3E7+zP4eeBktQkU4HPE98fXIf9QW09g9i3PEfK+TjXsyQ1wXTgG8T3A+uBY/M1Q704l/gdPwRciSsHSlKdbQP8mGr6gLPyNUO92on0KV8VB8BtwBPzNUWSNEFPBm6nmmv//aQFhNQAxwHrqOZAeAx4B87/LEl1MACcAqymmmv+OuC52VqjEP9MNQfDSDkf2D1bayRJG9sT+C7VXutPy9UYxZkKXEG1B8Zy0sExPU+TJEmkOVpOAZZS7TX+cmBKniYpWvT0j2OV60lrCPhYQJKqMwC8mGo++d64/A7YOU+zVJWnkOZtrvpgGSItM3kyZoySFGmQdJN1FXmu5cuBo7O0TJU7gfTyXo4DZwi4iTRblNmjJPVuF+DtwM3ku36vIa0cqBb5c6qZJGhzZS1pTYE/BbarvomS1HjbA68CfkC6hua8Zq8f/m210DvIezCNLuuAa4B/A16EXxBIEsAepFHajwLXkv9GbXR5e8VtrZUuvrT2PurzWcdy0tDWAuBu0huty4b/uZi06tTC4T+zrFAd1ZsZpEmptiXNTjavbHWkorYC5pKW5p1LugGaDxwAzC5Yr9HeC3ygdCVy6mICAPBG4N9pzst6Q6QvDS4iTXV8ddnqaBO2IT03PAp4KnAYLhoiNcE64E3AmaUronxeBqyi3FBTP+WnwPPjN4kmaRA4HvgKzT2WLJYul5XAS1AnPYs01F76IOy1/Bd+aVDKHwG3UP4YsFgsvZVFpBVk1WEHA7+h/MHYa/kdHsQ5HQFcRvn9brFYei/Xkt5BkJgFfJ7yB2WvZSVpdixVZwbwIfJ/lmSxWGLLmcBMpI2cTL5ZA6PLKtIqiIr3FOAGyu9ji8XSe1kCvBJpMw4C/ofyB2svZSHOLxDJu36LpR3lp6RPDqVxDQJvAB6m/IE72fLDCrZHF3nXb7E0vywEXku6pkuTsj3p3YCSM1P1Uk6qYmN0hHf9FkvzyzrgM6SJuKS+PA24kvIH9UTL9Zjx9sK7foul+eVy0oRcUqhnkmbjK32AT6Q8r6Jt0EYzgA/iXb/F0uRyIXAMUsWeTJqEp86PBs6urPXt4l2/xdLcsh74NvBEpMz2IK0wuIDyJ8LG5QG6u9bDRHjXb7E0t9xFOn/3QypsAHg68GnS9JKlT46Rsn+VjW6wJ5Pekyi9fywWy8TLI8CngKPx5iaEGzHeVNJKcM8ZLscA0wvV5QTgu4V+u45mkJaCfjvNWQlS6qq1pCl7LxkuVwKri9aoZUwAqjeLNDrwDOBQ4BBgr0y//ZekUQmlu/4vkNZ+kFQ/dwLXkTr9K4CfACtKVqjtXK+8eitIXw5cNOq/zSElAXsDewLbkb5X3Za0T2aSFp3Ztc/fntvn32+D0nf9K4GbSJNKSV21inQurCWdCwuH/3kXcMdwWV6sdlLNfJr+n5mdmr3W9VLyWf/lwIuAaVU3UpJ64QiA2mg6G+76cx/jDwNvBb6c+XclaVJMANQ2hwFnAYcX+O3vkdaQuLfAb0vSpDhdrNpiGmlOhl+Qv/N/lNTxvwg7f0kN4QiA2sC7fkmaJEcA1GTe9UtSjxwBUFN51y9JfXAEQE3jXb8kBXAEQE3iXb8kBXEEQE3gXb8kBXMEQHXnXb8kVcARANWVd/2SVCFHAFRH3vVLUsUcAVCdeNcvSZk4AqC6KH3X/3rgvgK/LUlFOAKg0upy12/nL6lTHAFQSYcBXwCOKPDb3vVL6jRHAFTC6Lv+3J2/d/2ShCMAys+7fkmqAUcAlIt3/ZJUI44AKIdDSW/4e9cvSTXhCICq5F2/JNWUIwCqinf9klRjjgAomnf9ktQAjgAoknf9ktQQjgAognf9ktQwjgCoX971S1IDOQKgXnnXL0kN5giAeuFdvyQ1nCMAmgzv+iWpJRwB0ER51y9JLeIIgMbjXb8ktZAjANoc7/olqaUcAdCmlLzrX4R3/ZJUOUcAtLFDgS8ARxb47e+SOn87fkmqmCMAGjH6rj935z9y138Cdv6SlIUjAALv+iWpcxwB6Dbv+iWpoxwB6C7v+iWpwxwB6B7v+iVJjgB0jHf9kiTAEYCumIp3/ZKkURwBaL9DSLP5edcvSVIDfBoY6rNcC6wJiDPZ8hDwivhNIklS+0UkACXKd4BdKtgekqRAvgOgKD7rl6QG8R0ARfBZvyQ1jCMA6od3/ZLUUI4AqFfe9UtSgzkCoMnyrl+SWsARAE2Gd/2S1BKOAGgivOuXpJZxBEDj8a5fklrIEQCNxbt+SWoxRwC0Kd71S1LLOQKg0bzrl6SOcARAI7zrl6QOcQRA3vVLUgc5AtBt3vVLUkc5AlBf6yuM/RDwCrzrl6TOcgSgvhZVFPcbwJuBByuKL0mS+vAKYCiwPAiclLUFkiRp0rYCVhDT+X8d2CFv9SVJUq8+hnf9kiR1zlbAHXjXL0lS5xxEelPfu35Jkjpmd+AiNt/xrwfOxbt+SZJa51jgc8DNwDLSS4LXAh8HDi9XLUlS0/x/rhawuwtRufkAAAAASUVORK5CYII="/>
                    </defs>
                </svg>
            </a>
            <div class="d-flex">
                <button onclick="showSoon()" class="border-0 px-3 py-2 rounded-pill bg-gray-200 gray-600">
                    Save <i class="fad fa-bookmark ms-2 gray-500"></i>
                </button>
                <button class="border-0 px-3 py-2 rounded-pill bg-gray-200 gray-600 ms-2">
                    Share <i class="fad fa-share ms-2 gray-500"></i>
                </button>
            </div>
        </div>
        <hr>
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="h1 text-app mb-4">
               Itinerary for a {{day_diff($plan['json_data']->daterange)}} days trip in {{removeSubstringAfterLastDash($plan["json_data"]->city->name)}}
            </h1>
            <div class="gray-500">
                <small>
                    {{removeSubstringAfterLastDash($plan["json_data"]->city->name)}}, {{getDateFromDateRange($plan["json_data"]->daterange)["startDate"]}} - {{getDateFromDateRange($plan["json_data"]->daterange)["endDate"]}}
                </small>
            </div>
        </div>
        <div>

            @php
                $count = 1;
                $countColor = 0;
                $startTime = 9;

            @endphp
            @foreach($plan["json_data_result"] as $time=>$timePlan)

                <a class="d-flex align-items-center active" data-bs-toggle="collapse" href="#collapseTime{{$count}}">
                    <div
                        class="logo logo-ssm rounded-circle text-white fw-bold me-2" style="background-color: {{$colors[$countColor]}}">{{$count}}</div>
                    {{$time}}
                </a>

                <div class="collapse show" id="collapseTime{{$count}}">
                    <div class="pt-2">
                        @if(empty($timePlan))
                            <div class="h5">This location is no longer a tourist area</div>
                        @endif
                        <div class="border-start position-relative px-3 position-relative">
                            @foreach($timePlan as $job)
                                <div class="row pt-4">
                                    <div class="col-md-4 col-xl-2 col-4">
                                        <div class="card-img w-100 rounded-10" style="background-image: url('{{!empty($job->photo->images->large->url) ? $job->photo->images->large->url : getImageError()}}')"></div>
                                    </div>
                                    <div class="col-md-8 col-xl-10 col-8">
                                        <div class="d-flex align-items-center">
                                            <span class="btn btn-sm rounded-2 bg-gray-200 pointer-event-none-all">{{$job->time->start}}</span>
                                            <i class="fas fa-arrow-right mx-2 fs-5 gray-700"></i>
                                            <span class="btn btn-sm rounded-2 bg-gray-200 pointer-event-none-all">{{$job->time->end}}</span>
                                        </div>
                                        <div class="fs-5 fw-bold mt-3">
                                            {{$job->name}}
                                        </div>
                                        <div class="lh-sm my-2">
                                            <small>
                                                {{$job->description}}
                                                <br>
                                                {{$job->ranking}}
                                            </small>
                                        </div>
                                        <div class="fw-bold gray-600">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center my-3 gray-500 fw-light text-nowrap">
                                    <i class="fal fa-cars me-2"></i>
                                    30 min <span class="d-md-inline-block d-none text-nowrap">&nbsp; to the next place</span>
                                    <div>
                                        <div class="circle-dot mx-2"></div>
                                    </div>
                                    <a target="_blank" href="https://www.google.com/maps/search/?api=1&query={{$job->latitude.",".$job->longitude}}" class="text-link text-nowrap">Check direction</a>
                                </div>
                            @endforeach
                                @if(!empty($timePlan))
                                    <div style="width: 8px;height: 8px;top: 80px;left: -4.5px" class="logo bg-gray-400 position-absolute"></div>
                                @endif
                        </div>
                    </div>
                </div>
                <hr>
                @php
                    $count += 1;
                    $countColor += 1;
                    if (empty($colors[$countColor])) $colors
                @endphp
            @endforeach
        </div>
    </div>
    @push("scripts")

    @endpush
@endsection
